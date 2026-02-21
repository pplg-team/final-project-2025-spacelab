# Column Mapping Fix - Database Schema Verification

## Issue Found

Error: `SQLSTATE[42703]: Undefined column: 7 ERROR: column "teacher_id" does not exist`

**Root Cause:** Query mencoba select column `teacher_id` dari table `timetable_entries`, padahal column tersebut tidak ada di table.

**Actual Schema:** Table `timetable_entries` memiliki column `teacher_subject_id` (foreign key ke `teacher_subjects`), bukan `teacher_id`.

---

## Fixed Files

### ✅ Admin/DashboardController.php
**Issue:** Line 26 - Select `teacher_id` yang tidak ada
```php
// ❌ Before
TimetableEntry::select('id', 'day_of_week', 'period_id', 'teacher_subject_id', 'teacher_id', 'room_history_id', 'template_id')
    ->with([
        'teacherSubject:id,subject_id',  // Missing teacher_id
        'teacher.user:id,name',           // teacher relationship doesn't exist
    ])

// ✅ After
TimetableEntry::select('id', 'day_of_week', 'period_id', 'teacher_subject_id', 'room_history_id', 'template_id')
    ->with([
        'teacherSubject:id,teacher_id,subject_id',  // Include teacher_id here
        'teacherSubject.teacher.user:id,name',      // Access teacher through teacherSubject
    ])
```

**Status:** ✅ Fixed

---

## Correct Table Schema

### timetable_entries table
```
Columns:
- id (PK)
- template_id (FK)
- day_of_week
- period_id (FK)
- teacher_subject_id (FK) ← Use this, NOT teacher_id
- room_history_id (FK)
- created_at
- updated_at

Relationships:
- belongsTo(TimetableTemplate, 'template_id')
- belongsTo(Period, 'period_id')
- belongsTo(TeacherSubject, 'teacher_subject_id')
- belongsTo(RoomHistory, 'room_history_id')
```

### teacher_subjects table
```
Columns:
- id (PK)
- teacher_id (FK) ← Teacher is accessed through this
- subject_id (FK)
- created_at
- updated_at

Relationships:
- belongsTo(Teacher, 'teacher_id')
- belongsTo(Subject, 'subject_id')
```

---

## Files to Verify

### High Priority (Likely to have same issue)

#### 1. Admin/ReportController.php
**Check:** TimetableEntry queries
```php
// Verify these queries don't select teacher_id
TimetableEntry::select(...)
TimetableEntry::with(...)
```

#### 2. Teacher/DashboardController.php
**Check:** TimetableEntry queries
```php
// Verify these queries don't select teacher_id
TimetableEntry::select(...)
TimetableEntry::with(...)
```

#### 3. Teacher/ScheduleController.php
**Check:** TimetableEntry queries
```php
// Verify these queries don't select teacher_id
TimetableEntry::select(...)
TimetableEntry::with(...)
```

#### 4. Student/DashboardController.php
**Check:** TimetableEntry queries
```php
// Verify these queries don't select teacher_id
TimetableEntry::select(...)
TimetableEntry::with(...)
```

#### 5. RoomController.php
**Check:** TimetableEntry queries
```php
// Verify these queries don't select teacher_id
TimetableEntry::select(...)
TimetableEntry::with(...)
```

#### 6. SearchStudentController.php
**Check:** TimetableEntry queries
```php
// Verify these queries don't select teacher_id
TimetableEntry::select(...)
TimetableEntry::with(...)
```

#### 7. SearchTeacherController.php
**Check:** TimetableEntry queries
```php
// Verify these queries don't select teacher_id
TimetableEntry::select(...)
TimetableEntry::with(...)
```

---

## Correct Pattern for TimetableEntry Queries

### ✅ Correct Way
```php
// Access teacher through teacherSubject relationship
$entries = TimetableEntry::select('id', 'day_of_week', 'period_id', 'teacher_subject_id', 'room_history_id', 'template_id')
    ->with([
        'period:id,ordinal,start_time,end_time',
        'teacherSubject:id,teacher_id,subject_id',  // Include teacher_id in select
        'teacherSubject.teacher.user:id,name',      // Access teacher through teacherSubject
        'teacherSubject.subject:id,name',
        'roomHistory.room:id,name',
        'template.class:id,name',
    ])
    ->get();

// In view/code
$teacher = $entry->teacherSubject?->teacher?->user?->name;
```

### ❌ Wrong Way
```php
// DON'T select teacher_id (doesn't exist)
TimetableEntry::select('id', 'teacher_id', ...)  // ❌ teacher_id doesn't exist

// DON'T try to access teacher directly
$entry->teacher  // ❌ teacher relationship doesn't exist

// DON'T include teacher.user in with
->with('teacher.user')  // ❌ teacher relationship doesn't exist
```

---

## Verification Checklist

Before deploying, verify:

- [ ] No query selects `teacher_id` from `timetable_entries`
- [ ] All teacher access goes through `teacherSubject` relationship
- [ ] `teacherSubject` select includes `teacher_id` column
- [ ] Eager loading uses `teacherSubject.teacher.user` not `teacher.user`
- [ ] Code accesses teacher via `$entry->teacherSubject->teacher` not `$entry->teacher`
- [ ] All TimetableEntry queries tested with real data
- [ ] No SQL errors in logs

---

## Testing Query

Run this to verify the fix works:

```php
// In tinker or test
$entry = TimetableEntry::select('id', 'day_of_week', 'period_id', 'teacher_subject_id', 'room_history_id', 'template_id')
    ->with([
        'period:id,ordinal,start_time,end_time',
        'teacherSubject:id,teacher_id,subject_id',
        'teacherSubject.teacher.user:id,name',
        'teacherSubject.subject:id,name',
        'roomHistory.room:id,name',
        'template.class:id,name',
    ])
    ->first();

// Should work without error
echo $entry->teacherSubject->teacher->user->name;
```

---

## Database Schema Reference

### All Foreign Keys in timetable_entries
```
teacher_subject_id → teacher_subjects.id
  → teacher_subjects.teacher_id → teachers.id
  → teachers.user_id → users.id

period_id → periods.id

template_id → timetable_templates.id
  → timetable_templates.class_id → classes.id

room_history_id → room_histories.id
  → room_histories.room_id → rooms.id
```

---

## Summary

**Issue:** Column `teacher_id` doesn't exist in `timetable_entries` table
**Solution:** Access teacher through `teacherSubject` relationship
**Status:** ✅ Fixed in Admin/DashboardController
**Action:** Verify other TimetableEntry queries use correct pattern

---

**Last Updated:** 2026-02-21
**Status:** Fix Applied
