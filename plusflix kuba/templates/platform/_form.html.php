<?php
/** @var $platform ?\App\Model\Platform */
?>
<style>
    .platform-form-grid {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    /* Override the default narrow form width */
    .edit .edit-form,
    .edit .back-link {
        max-width: 1000px;
    }

    .platform-logo-wrapper {
        position: relative;
        width: 100%;
        aspect-ratio: 1/1; /* Square for platform logos */
        background: rgba(255, 255, 255, 0.06);
        border: 2px dashed rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .platform-logo-wrapper:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .platform-logo-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Contain to show full logo */
        padding: 1rem;
        position: absolute;
        top: 0;
        left: 0;
    }

    .cover-placeholder {
        z-index: 1;
        text-align: center;
        padding: 1rem;
        color: rgba(255, 255, 255, 0.5);
        pointer-events: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .cover-placeholder svg {
        width: 48px;
        height: 48px;
        opacity: 0.7;
    }

    .hidden-file-input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
        width: 100%;
        height: 100%;
    }

    .form-right-column {
        display: flex;
        flex-direction: column;
        gap: 0; /* Gap handled by form-group margin */
    }
    
    .submit-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
    }

    .submit-container input[type="submit"] {
        width: auto !important;
        padding-left: 2.5rem !important;
        padding-right: 2.5rem !important;
        margin-top: 0 !important;
    }

    @media (max-width: 800px) {
        .platform-form-grid {
            grid-template-columns: 1fr;
        }
        .platform-logo-wrapper {
            max-width: 280px;
            margin: 0 auto;
        }
    }
</style>

<div class="platform-form-grid">
    <!-- Left Column: Logo Upload -->
    <div class="form-left-column">
        <div class="platform-logo-wrapper" id="logo-wrapper">
            <?php 
                $currentLogo = $platform ? $platform->getLogoPath() : '';
                $hasLogo = !empty($currentLogo);
            ?>
            
            <img id="preview-logo" src="<?= $hasLogo ? $currentLogo : '' ?>" alt="Logo" style="<?= $hasLogo ? 'display:block' : 'display:none' ?>">
            
            <div class="cover-placeholder" id="logo-placeholder" style="<?= $hasLogo ? 'display:none' : 'display:flex' ?>">
                <!-- Icon for upload -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Kliknij, aby dodać logo</span>
            </div>

            <input type="file" id="logo_file" name="logo_file" accept="image/*" class="hidden-file-input">
        </div>
        <?php if ($hasLogo): ?>
            <input type="hidden" name="platform[logo_path]" value="<?= $currentLogo ?>">
        <?php endif; ?>
    </div>

    <!-- Right Column: Details -->
    <div class="form-right-column">
        <div class="form-group">
            <label for="name">Nazwa platformy</label>
            <input type="text" id="name" name="platform[name]" value="<?= $platform ? $platform->getName() : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="url">Adres strony głównej (URL)</label>
            <input type="text" id="url" name="platform[url]" value="<?= $platform ? $platform->getUrl() : '' ?>">
        </div>
        
        <!-- text input for logo_path removed in favor of file upload to match movie form -->
        
        <div class="form-group submit-container">
            <input type="submit" value="Zapisz">
        </div>
    </div>
</div>

<script>
    document.getElementById('logo_file').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview-logo');
                const placeholder = document.getElementById('logo-placeholder');
                
                preview.src = e.target.result;
                preview.style.display = 'block';
                
                if (placeholder) {
                    placeholder.style.display = 'none';
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>