<!-- main_menu.blade.php -->

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h4>{{ tr('Main Menu Settings') }}</h4>

            <div id="menu-items">
                <!-- Include menu items dynamically using jQuery -->
            </div>

            <button id="add-menu-item" class="btn btn-success">{{ tr('Add Menu Item') }}</button>
        </div>

        <div class="col-md-6">
            <h4>Edit Menu Item</h4>

            <form id="edit-menu-form">
                <div class="form-group">
                    <label for="menu-title">{{ tr('Menu Title') }}:</label>
                    <input type="text" class="form-control" id="menu-title" name="menu-title">
                </div>

                <div class="form-group">
                    <label for="menu-link">{{ tr('Menu Link') }}:</label>
                    <input type="text" class="form-control" id="menu-link" name="menu-link">
                </div>

                <button type="button" id="update-menu-item" class="btn btn-primary">{{ tr('Update Menu Item') }}</button>
                <button type="button" id="delete-menu-item" class="btn btn-danger">{{ tr('Delete Menu Item') }}</button>
            </form>
        </div>
    </div>
</div>

<script>
    // jQuery script for dynamic menu editing
    $(document).ready(function () {
        // Function to fetch menu items from the backend
        function fetchMenuItems() {
            // Sample AJAX call to fetch menu items (replace this with your actual AJAX call)
            $.ajax({
                url: '/api/menu-items', // Replace with your API endpoint
                method: 'GET',
                success: function (response) {
                    menuItems = response; // Assuming response is an array of menu items
                    renderMenuItems();
                },
                error: function () {
                    console.error('Error fetching menu items');
                }
            });
        }

        // Function to render menu items
        function renderMenuItems() {
            $('#menu-items').empty();
            menuItems.forEach(function (item) {
                $('#menu-items').append('<div class="menu-item" data-id="' + item.id + '">' + item.title + '</div>');
            });
        }

        // Call fetchMenuItems initially to load menu items
        fetchMenuItems();


        renderMenuItems();

        // Function to update the Edit Menu Item form with the selected item data
        function updateEditForm(item) {
            $('#menu-title').val(item.title);
            $('#menu-link').val(item.link);
        }

        // Handle Add Menu Item button click
        $('#add-menu-item').on('click', function () {
            var newItem = {
                id: menuItems.length + 1,
                title: 'New Item ' + (menuItems.length + 1),
                link: '#',
            };

            menuItems.push(newItem);
            renderMenuItems();
        });

        // Handle Edit Menu Item click
        $('#menu-items').on('click', '.menu-item', function () {
            var itemId = $(this).data('id');
            var selectedItem = menuItems.find(function (item) {
                return item.id === itemId;
            });

            updateEditForm(selectedItem);
        });

        // Handle Update Menu Item click
        $('#update-menu-item').on('click', function () {
            var itemId = $('.menu-item.active').data('id');
            var updatedItem = {
                title: $('#menu-title').val(),
                link: $('#menu-link').val(),
            };

            var index = menuItems.findIndex(function (item) {
                return item.id === itemId;
            });

            if (index !== -1) {
                menuItems[index] = { ...menuItems[index], ...updatedItem };
                renderMenuItems();
                updateEditForm(menuItems[index]);
            }
        });

        // Handle Delete Menu Item click
        $('#delete-menu-item').on('click', function () {
            var itemId = $('.menu-item.active').data('id');
            menuItems = menuItems.filter(function (item) {
                return item.id !== itemId;
            });

            renderMenuItems();
            $('#menu-title').val('');
            $('#menu-link').val('');
        });
    });
</script>
