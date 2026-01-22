<?php $__env->startSection('css'); ?>
    <style>
        .image-container,  {
            position: relative;
            display: inline-block;
            /* left: 0vw; */
            margin: 5px
        }

        .image-container img {
            max-width: 250px;
            max-height: 250px;
            border: 2px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            background: #fff;
        }
        .close-button {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 12px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row m-3">

<?php if(!empty($pop_up->id)): ?>
    <form action="<?php echo e(route('backend.website_setting.popup.update', $pop_up->id)); ?>" method="POST" enctype="multipart/form-data" class="mt-2 p-2 col-lg-12">
        <?php echo method_field('put'); ?>
<?php else: ?>
<form action="<?php echo e(route('backend.website_setting.popup.create')); ?>" method="POST" enctype="multipart/form-data" class="mt-2 p-2 col-lg-12">
<?php endif; ?>
        <?php echo csrf_field(); ?>

        <div class="container-fluid" style="background-color: white; padding:20px; border-radius: 7px;">
            <!-- Description -->
            <div class="form-group">
                <label>Description</label>
                <textarea type="text" name="description" class="form-control" value="<?php echo e(old('description')); ?>" style="height: 150px;"><?php echo e($pop_up->description??''); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color: red;"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <?php if(isset($pop_up->id)): ?>
            <div id="image-preview">
                <div id="image-container2">
                    <img src="<?php echo e(asset('uploads/frontend/img/' . $pop_up->image)); ?>" alt="image" width="250" height="250">
                </div>
            </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="">Choose Image</label>
                <input id="image-input" type="file" name="image" class="form-control" accept="image/*">
                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color: red;"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <input type="hidden" name="status" id="status" value="<?php echo e($pop_up->is_active??''); ?>">
            <div class="form-check form-switch mt-2 d-flex align-items-center">
                <label class="form-check-label" for="flexSwitchCheckDefault">Status</label>
                <input name="status2" class="form-check-input mx-1" type="checkbox" role="switch" id="on_off_button" <?php echo e((isset($pop_up->is_active) && $pop_up->is_active ) ? 'checked' : ''); ?>/>
            </div>

            <button type="submit" class="btn btn-success mt-3" style="color: white;">Update</button>

        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        let imageInput = document.getElementById('image-input'); // Get the image input element
        let images = new DataTransfer(); // Initialize DataTransfer object to store images

        imageInput.addEventListener('change', function(event) {
            const imagePreviewContainer = document.getElementById(
                'image-preview'); // Get the container for image previews

            $('#image-container2').css('display', 'none');
            const files = event.target.files; // Get the files from the input event
            const currentSelectedImages =
                new DataTransfer(); // Create a new DataTransfer object for current selected images

            const array = Array.from(files); // Convert the FileList to an array

            array.forEach((file, index) => {
                currentSelectedImages.items.add(file); // Add each file to currentSelectedImages

                const reader = new FileReader(); // Create a FileReader to read the file
                reader.onload = function(e) {
                    const imageContainer = document.createElement(
                        'div'); // Create a container div for the image
                    imageContainer.classList.add('image-container'); // Add class to the image container

                    const img = document.createElement('img'); // Create an img element
                    img.src = e.target.result; // Set the src of the img element to the file data
                    img.alt = file.name; // Set the alt attribute of the img element

                    const closeButton = document.createElement(
                        'button'); // Create a button to close/remove the image
                    closeButton.innerHTML = '&times;'; // Set the button's inner HTML to a times symbol
                    closeButton.classList.add('close-button'); // Add class to the close button
                    closeButton.addEventListener('click', function() {
                        imageContainer.remove(); // Remove the image container from the DOM
                        removeFile(file); // Call removeFile to remove the file from images
                    });

                    imageContainer.appendChild(img); // Add the img element to the image container
                    imageContainer.appendChild(
                        closeButton); // Add the close button to the image container

                    imagePreviewContainer.appendChild(
                        imageContainer); // Add the image container to the preview container
                };
                reader.readAsDataURL(file); // Read the file as a data URL
            });

            for (let i = 0; i < currentSelectedImages.files.length; i++) {
                images.items.add(currentSelectedImages.files[i]); // Add the current selected images to images
            }

            function removeFile(file) {
                let updatedImages = new DataTransfer(); // Create a new DataTransfer object for updated images
                const filesArray = Array.from(imageInput.files); // Convert the input files to an array
                const selectedToRemoveFileName = file.name; // Get the name of the file to remove

                for (let i = 0; i < images.files.length; i++) { // Loop through images
                    if (selectedToRemoveFileName !== images.files[i]
                        .name) { // Check if the current file is not the one to remove
                        updatedImages.items.add(images.files[i]); // Add the file to updatedImages
                    }
                }

                images = updatedImages; // Reassign images to updatedImages
                imageInput.files = images.files; // Update the input files with the new DataTransfer object
                console.log(imageInput.files); // Log the updated files
            }

            imageInput.files = images.files; // Update the input files with the updated images
        });
    </script>

    <script>
        $('#on_off_button').on('change', function(event){
            $('#status_form').submit();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/china_hub/resources/views/backend/pages/website_setting/pop_up/create_popup.blade.php ENDPATH**/ ?>