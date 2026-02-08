/**
 * Room Index Page - Collapsible buildings and modal handling
 */

document.addEventListener("DOMContentLoaded", function () {
    // Elements
    const searchInput = document.getElementById("searchInput");
    const typeFilter = document.getElementById("typeFilter");

    // Debounce function
    let debounceTimer;
    function debounce(func, delay) {
        return function (...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Toggle building collapse
    window.toggleBuilding = function (buildingId) {
        const roomsDiv = document.getElementById(`rooms-${buildingId}`);
        const chevron = document.getElementById(`chevron-${buildingId}`);

        if (roomsDiv.classList.contains("hidden")) {
            roomsDiv.classList.remove("hidden");
            chevron.classList.add("rotate-180");
        } else {
            roomsDiv.classList.add("hidden");
            chevron.classList.remove("rotate-180");
        }
    };

    // Filter function
    function filterContent() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedType = typeFilter.value;

        const buildingCards = document.querySelectorAll(".building-card");

        buildingCards.forEach((card) => {
            const buildingName = card.dataset.buildingName || "";
            const buildingCode = card.dataset.buildingCode || "";
            const roomRows = card.querySelectorAll(".room-row");

            let buildingMatches = false;
            let hasVisibleRooms = false;

            // Check if building matches search
            if (searchTerm) {
                buildingMatches =
                    buildingName.includes(searchTerm) ||
                    buildingCode.includes(searchTerm);
            } else {
                buildingMatches = true;
            }

            // Filter rooms within building
            roomRows.forEach((row) => {
                const roomName = row.dataset.roomName || "";
                const roomCode = row.dataset.roomCode || "";
                const roomType = row.dataset.roomType || "";

                let matchesSearch = true;
                let matchesType = true;

                if (searchTerm) {
                    matchesSearch =
                        roomName.includes(searchTerm) ||
                        roomCode.includes(searchTerm) ||
                        buildingMatches;
                }

                if (selectedType) {
                    matchesType = roomType === selectedType;
                }

                if (matchesSearch && matchesType) {
                    row.style.display = "";
                    hasVisibleRooms = true;
                } else {
                    row.style.display = "none";
                }
            });

            // Show building if it matches search or has visible rooms
            if (buildingMatches || hasVisibleRooms) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    }

    // Event listeners for filtering
    if (searchInput) {
        searchInput.addEventListener("input", debounce(filterContent, 300));
    }
    if (typeFilter) {
        typeFilter.addEventListener("change", filterContent);
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const alerts = ["successAlert", "errorAlert", "validationAlert"];
        alerts.forEach((id) => {
            const alert = document.getElementById(id);
            if (alert) {
                alert.style.transition = "opacity 0.5s";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);

    // Make functions globally accessible
    window.editBuilding = editBuilding;
    window.deleteBuilding = deleteBuilding;
    window.viewRoom = viewRoom;
    window.editRoom = editRoom;
    window.deleteRoom = deleteRoom;

    // Edit building
    async function editBuilding(buildingId) {
        try {
            const response = await fetch(`/admin/buildings/${buildingId}`);
            const building = await response.json();

            if (building.error) {
                alert("Gagal memuat data gedung");
                return;
            }

            // Populate form
            document.getElementById("editBuildingCode").value = building.code;
            document.getElementById("editBuildingName").value = building.name;
            document.getElementById("editBuildingFloors").value =
                building.total_floors;
            document.getElementById("editBuildingDescription").value =
                building.description || "";

            // Set form action
            document.getElementById("editBuildingForm").action =
                `/admin/buildings/${building.id}`;

            // Open modal
            window.dispatchEvent(
                new CustomEvent("open-modal", {
                    detail: "edit-building-modal",
                }),
            );
        } catch (error) {
            console.error("Error loading building for edit:", error);
            alert("Gagal memuat data gedung");
        }
    }

    // Delete building
    function deleteBuilding(buildingId, buildingName) {
        document.getElementById("deleteBuildingName").textContent =
            buildingName;
        document.getElementById("deleteBuildingForm").action =
            `/admin/buildings/${buildingId}`;

        window.dispatchEvent(
            new CustomEvent("open-modal", { detail: "delete-building-modal" }),
        );
    }

    // View room details
    async function viewRoom(roomId) {
        try {
            const response = await fetch(`/admin/rooms/${roomId}`);
            const room = await response.json();

            if (room.error) {
                alert("Gagal memuat data ruangan");
                return;
            }

            const content = `
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kode</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${room.code}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nama</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${room.name}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Gedung</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${room.building_name || "-"}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Lantai</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${room.floor ?? "-"}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kapasitas</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${room.capacity ?? "-"}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipe</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${room.type ? room.type.charAt(0).toUpperCase() + room.type.slice(1) : "-"}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                        <p class="mt-1">
                            ${
                                room.is_active
                                    ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Aktif</span>'
                                    : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Nonaktif</span>'
                            }
                        </p>
                    </div>
                </div>
                ${
                    room.notes
                        ? `
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${room.notes}</p>
                    </div>
                `
                        : ""
                }
            `;

            document.getElementById("viewRoomContent").innerHTML = content;
            window.dispatchEvent(
                new CustomEvent("open-modal", { detail: "view-room-modal" }),
            );
        } catch (error) {
            console.error("Error viewing room:", error);
            alert("Gagal memuat data ruangan");
        }
    }

    // Edit room
    async function editRoom(roomId) {
        try {
            const response = await fetch(`/admin/rooms/${roomId}`);
            const room = await response.json();

            if (room.error) {
                alert("Gagal memuat data ruangan");
                return;
            }

            // Populate form
            document.getElementById("editRoomCode").value = room.code;
            document.getElementById("editRoomName").value = room.name;
            document.getElementById("editRoomBuilding").value =
                room.building_id;
            document.getElementById("editRoomFloor").value = room.floor ?? "";
            document.getElementById("editRoomCapacity").value =
                room.capacity ?? "";
            document.getElementById("editRoomType").value = room.type;
            document.getElementById("editRoomNotes").value = room.notes || "";
            document.getElementById("editIsActive").checked = room.is_active;

            // Set form action
            document.getElementById("editRoomForm").action =
                `/admin/rooms/${room.id}`;

            // Open modal
            window.dispatchEvent(
                new CustomEvent("open-modal", { detail: "edit-room-modal" }),
            );
        } catch (error) {
            console.error("Error loading room for edit:", error);
            alert("Gagal memuat data ruangan");
        }
    }

    // Delete room
    function deleteRoom(roomId, roomName) {
        document.getElementById("deleteRoomName").textContent = roomName;
        document.getElementById("deleteRoomForm").action =
            `/admin/rooms/${roomId}`;

        window.dispatchEvent(
            new CustomEvent("open-modal", { detail: "delete-room-modal" }),
        );
    }
});
