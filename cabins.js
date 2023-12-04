console.log('I am javascript and I am loading correctly :)');

// Function to toggle visibility of cabin type input
function toggleCabinTypeInput() {
    var actionType = document.getElementById('actionType').value;
    var cabinTypeInput = document.getElementById('cabinTypeInput');

    // If the selected action is 'Add new cabin', show the input; otherwise, hide it
    cabinTypeInput.style.display = (actionType === 'AddNewCabin') ? 'block' : 'none';
}

// Function to handle form submission
function cabins_plugin_handle_form_submission() {
    // Add your form submission logic here
    console.log('Form submission logic goes here.');
}

document.addEventListener('DOMContentLoaded', function () {
    // Handling multi-selection for inclusions
    const inclusionsSelect = document.querySelector('.inclusions');

    // Check if the element exists before adding event listeners
    if (inclusionsSelect) {
        inclusionsSelect.addEventListener('mousedown', function (event) {
            event.preventDefault(); // Prevent default to enable custom behavior

            const option = event.target;
            if (option.tagName === 'OPTION') {
                option.selected = !option.selected; // Toggle the selected state

                // Dispatch a custom 'change' event to update the state
                const changeEvent = new Event('change', { bubbles: true });
                option.dispatchEvent(changeEvent);
            }
        });

        // Log selected options in Inclusions
        inclusionsSelect.addEventListener('change', function () {
            const selectedOptions = Array.from(inclusionsSelect.selectedOptions).map(option => option.value);
            console.log('Selected inclusions:', selectedOptions);
        });
    }

    // Update custom file label with the selected file name
    const fileInput = document.querySelector('.imagebutton input[type="file"]');
    const uploadButton = document.getElementById('uploadButton'); // Get the upload button

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            // Change the button text to "Uploaded" and color to green if a file is selected
            if (fileInput.files[0]) {
                uploadButton.textContent = 'Uploaded';
                uploadButton.style.backgroundColor = 'green';
                uploadButton.style.color = 'white';
            } else {
                // Reset to the original text and color if no file is selected
                uploadButton.textContent = 'Choose File';
                uploadButton.style.backgroundColor = '';
                uploadButton.style.color = '';
            }
        });
    }

    // Form submission
    const form = document.getElementById('cabins-form');
    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            // Serialize the form data
            const formData = new FormData(form);

            // Create an XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Configure it: POST-method, the URL, asynchronous
            xhr.open('POST', window.location.href, true);

            // Define what happens on successful data submission
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    console.log(xhr.responseText); // Log the response for debugging
                    alert('Form submitted successfully!'); // You can replace this with your desired success action
                } else {
                    console.error(xhr.statusText); // Log any errors for debugging
                }
            };

            // Define what happens in case of an error
            xhr.onerror = function () {
                console.error('Network error'); // Log network errors for debugging
            };

            // Send the form data
            xhr.send(formData);
        });
    }

    // Event listener for the change in the action type
    document.getElementById('actionType').addEventListener('change', toggleCabinTypeInput);

    // Initialize visibility based on the initial value
    toggleCabinTypeInput();
});
