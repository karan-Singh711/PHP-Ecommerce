<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Attribute Manager</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#98A1BC',
                        secondary: '#555879',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Menu Button -->
        <button id="mobileMenuBtn" class="lg:hidden fixed top-4 left-4 z-50 bg-secondary text-white p-3 rounded-lg shadow-lg">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Overlay for mobile -->
        <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out w-64 bg-secondary text-white flex-shrink-0 overflow-y-auto z-40">
            <div class="p-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
                <button id="closeSidebarBtn" class="lg:hidden text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="mt-6">
                <a href="#" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Orders
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Products
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    Categories
                </a>
                <a href="#" class="flex items-center px-6 py-3 bg-gray-700 text-white border-l-4 border-primary">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Attributes
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Users
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-4 sm:p-6 lg:p-8 lg:ml-0 ml-0">
                <!-- Header -->
                <div class="mb-6 sm:mb-8 mt-12 lg:mt-0">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Attribute Manager</h2>
                    <p class="text-gray-600 mt-2 text-sm sm:text-base">Manage category attributes and their values</p>
                </div>

                <!-- Category Selection Card -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Select Category</h3>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="categorySearch"
                            placeholder="Search or select category..."
                            class="w-full px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                            autocomplete="off"
                        >
                        <i class="fas fa-search absolute right-4 top-3 sm:top-4 text-gray-400"></i>
                        
                        <!-- Dropdown Results - PHP will populate this -->
                        <div id="categoryDropdown" class="hidden absolute z-10 w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
        
                        </div>
                    </div>

                    <!-- Selected Category Display -->
                    <div id="selectedCategory" class="hidden mt-4 p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs sm:text-sm text-gray-600">Selected Category:</span>
                                <span id="categoryName" class="ml-2 text-base sm:text-lg font-semibold text-blue-600"></span>
                                <input type="hidden" id="selectedCategoryId" value="">
                            </div>
                            <button onclick="clearCategory()" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div id="mainContent" class="hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        
                        <!-- Left Panel: Attributes List -->
                        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Attributes</h3>
                                <button onclick="openAddAttributeModal()" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 text-sm">
                                    <i class="fas fa-plus"></i>
                                    Add Attribute
                                </button>
                            </div>

                            <!-- Attributes List - PHP will populate this -->
                            <div id="attributesList" class="space-y-3">
                                <!-- PHP: Loop attributes here
                                Example structure:
                                <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition cursor-pointer" data-attribute-id="1" onclick="selectAttribute(this)">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-microchip text-blue-600 text-sm sm:text-base"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-800 text-sm sm:text-base">RAM</h4>
                                                <p class="text-xs sm:text-sm text-gray-500">3 values</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 sm:gap-2">
                                            <button class="text-blue-600 hover:text-blue-800 p-1 sm:p-2">
                                                <i class="fas fa-edit text-sm"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-800 p-1 sm:p-2">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                -->

                                <!-- No Attributes Message -->
                                <div id="noAttributes" class="text-center py-8 sm:py-12">
                                    <i class="fas fa-inbox text-gray-300 text-4xl sm:text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-base sm:text-lg">No attributes found</p>
                                    <p class="text-gray-400 text-xs sm:text-sm">Add your first attribute to get started</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Attribute Values -->
                        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                            <div id="noAttributeSelected" class="text-center py-8 sm:py-12">
                                <i class="fas fa-hand-pointer text-gray-300 text-4xl sm:text-5xl mb-4"></i>
                                <p class="text-gray-500 text-base sm:text-lg">Select an attribute</p>
                                <p class="text-gray-400 text-xs sm:text-sm">Click on an attribute to view and manage its values</p>
                            </div>

                            <div id="valuesPanel" class="hidden">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Values for <span id="selectedAttributeName" class="text-blue-600"></span></h3>
                                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage attribute values</p>
                                        <input type="hidden" id="selectedAttributeId" value="">
                                    </div>
                                    <button onclick="openAddValueModal()" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 text-sm">
                                        <i class="fas fa-plus"></i>
                                        Add Value
                                    </button>
                                </div>

                                <!-- Values List - PHP will populate this -->
                                <div id="valuesList" class="space-y-2 mt-6">
                                    <!-- PHP: Loop attribute values here
                                    Example structure:
                                    <div class="flex items-center justify-between p-2 sm:p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            <span class="w-6 h-6 sm:w-8 sm:h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-medium">4</span>
                                            <span class="font-medium text-gray-700 text-sm sm:text-base">4 GB</span>
                                        </div>
                                        <div class="flex gap-1 sm:gap-2">
                                            <button class="text-blue-600 hover:text-blue-800 p-1 sm:p-2">
                                                <i class="fas fa-edit text-sm"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-800 p-1 sm:p-2">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Attribute Modal -->
    <div id="addAttributeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Add New Attribute</h3>
                <button onclick="closeAddAttributeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="addAttributeForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attribute Name</label>
                    <input 
                        type="text" 
                        id="attributeNameInput"
                        name="attributeName"
                        placeholder="e.g., Processor, Screen Size"
                        class="w-full px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        required
                    >
                    <input type="hidden" name="categoryId" id="attributeCategoryId">
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeAddAttributeModal()" class="flex-1 px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 text-sm sm:text-base bg-blue-600 text-white rounded-lg hover:bg-blue-700" >Add Attribute</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Value Modal -->
    <div id="addValueModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Add New Value</h3>
                <button onclick="closeAddValueModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="addValueForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                    <input 
                        type="text" 
                        id="valueInput"
                        name="attributeValue"
                        placeholder="e.g., 32 GB, Intel i7"
                        class="w-full px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none"
                        required
                    >
                    <input type="hidden" name="attributeId" id="valueAttributeId">
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeAddValueModal()" class="flex-1 px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 text-sm sm:text-base bg-green-600 text-white rounded-lg hover:bg-green-700">Add Value</button>
                </div>
            </form>
        </div>
    </div>
        <!-- <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Select Category</h3>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="categorySearch"
                            placeholder="Search or select category..."
                            class="w-full px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none"
                            autocomplete="off"
                        >
                        <i class="fas fa-search absolute right-4 top-3 sm:top-4 text-gray-400"></i> -->
                        
                        <!-- Dropdown Results - PHP will populate this -->
                        <!-- <div id="categoryDropdown" class="hidden absolute z-10 w-full mt-2 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"> -->
                            <!-- PHP: Loop categories here and generate options -->
                            
                            <!-- <div class="p-3 hover:bg-blue-50 cursor-pointer border-b" data-category-id="1" onclick="selectCategory(this)">
                                <div class="font-medium text-sm sm:text-base">Electronics</div>
                                <div class="text-xs sm:text-sm text-gray-500">Main Category</div>
                            </div> -->
                           
                        <!-- </div>
                    </div> -->

    <script>
        
        $(document).ready(function() {
            // Mobile Menu Toggle
            $('#mobileMenuBtn').on('click', function() {
                $('#sidebar').removeClass('-translate-x-full');
                $('#sidebarOverlay').removeClass('hidden');
            });

            $('#closeSidebarBtn, #sidebarOverlay').on('click', function() {
                $('#sidebar').addClass('-translate-x-full');
                $('#sidebarOverlay').addClass('hidden');
            });

            // Category search dropdown
            const categorySearch = $('#categorySearch');
            const categoryDropdown = $('#categoryDropdown');
            let timer;
            categorySearch.on('input',function(){
                let value =$(this).val()
                clearTimeout(timer)
                if(value.length > 3){
                    timer = setTimeout(() => {
                        $.ajax({
                            url:'../api.php',
                            method:'POST', 
                            data:{action : 'attributeCategory',categories:value},
                            dataType:'json',
                            success:function(response){
                                categoryDropdown.removeClass('hidden')
                                console.log(response)
                                categoryDropdown.html('')
                                if(response.data && response.data.length !== 0){
                                    let data = response.data
                                    $.each(data,function(index,item) {
                                        let categoryType = ""
                                        if(item.parent_id === null){
                                            categoryType = 'Main Category'
                                        }else{
                                            categoryType = 'Sub Category'
                                        }
                                        optionHtml = `
                                            <div class="p-3 hover:bg-blue-50 cursor-pointer border-b" data-category-id="${item.id}" onclick="selectCategory(this)">
                                                <div class="font-medium text-sm sm:text-base">${item.category_name}</div>
                                                <div class="text-xs sm:text-sm text-gray-500">${categoryType}</div>
                                            </div>
                                        `  
                                        categoryDropdown.append(optionHtml)
                                       
                                    })
                                    
                                }
                            } ,
                            error: function(xhr,status,error){
                                console.log(error)
                            }
                        })
                    }, 500);
                }
            })

            


            categorySearch.on('focus', function() {
                categoryDropdown.removeClass('hidden');
            });

            categorySearch.on('blur', function() {
                setTimeout(() => categoryDropdown.addClass('hidden'), 200);
            });

            // categorySearch.on('input', function() {
            //     const searchTerm = $(this).val().toLowerCase();
            //     $('#categoryDropdown > div').each(function() {
            //         const text = $(this).text().toLowerCase();
            //         if (text.includes(searchTerm)) {
            //             $(this).show();
            //         } else {
            //             $(this).hide();
            //         }
            //     });
            //     categoryDropdown.removeClass('hidden');
            // });

            // Add Attribute Form Submit
            $('#addAttributeForm').on('submit', function(e) {
                e.preventDefault();
                console.log(categoryId)
            });

            // Add Value Form Submit
            $('#addValueForm').on('submit', function(e) {
                e.preventDefault();
                // Your backend code here to process form data
                // Form data available: attributeValue, attributeId
            });
                
        });

       
            function selectCategory(element) {
                const categoryId = $(element).data('category-id');
                const categoryName = $(element).find('.font-medium').first().text();
                console.log(categoryId)
                $('#categorySearch').val(categoryName);
                $('#categoryName').text(categoryName);
                $('#selectedCategoryId').val(categoryId);
                $('#attributeCategoryId').val(categoryId);
                $('#selectedCategory').removeClass('hidden');
                $('#mainContent').removeClass('hidden');
                $('#categoryDropdown').addClass('hidden');
            }
        function clearCategory() {
            $('#categorySearch').val('');
            $('#selectedCategory').addClass('hidden');
            $('#mainContent').addClass('hidden');
            $('#selectedCategoryId').val('');
        }

        function selectAttribute(element) {
            const attributeId = $(element).data('attribute-id');
            const attributeName = $(element).find('h4').text();
            
            $('#noAttributeSelected').addClass('hidden');
            $('#valuesPanel').removeClass('hidden');
            $('#selectedAttributeName').text(attributeName);
            $('#selectedAttributeId').val(attributeId);
            $('#valueAttributeId').val(attributeId);
        }

        function openAddAttributeModal() {
            const categoryId = $('#selectedCategoryId').val();
            $('#attributeCategoryId').val(categoryId);
            $('#addAttributeModal').removeClass('hidden');
        } 

        function closeAddAttributeModal() {
            $('#addAttributeModal').addClass('hidden');
            $('#attributeNameInput').val('');
        }

        function openAddValueModal() {
            const attributeId = $('#selectedAttributeId').val();
            $('#valueAttributeId').val(attributeId);
            $('#addValueModal').removeClass('hidden');
        }

        function closeAddValueModal() {
            $('#addValueModal').addClass('hidden');
            $('#valueInput').val('');
        }
    </script>
</body>
</html>