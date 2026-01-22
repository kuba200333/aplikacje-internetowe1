<?php
/** @var $movie ?\App\Model\Movie */
/** @var $categories \App\Model\Category[] */
?>
<style>
    .movie-form-grid {
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

    .movie-cover-wrapper {
        position: relative;
        width: 100%;
        aspect-ratio: 2/3;
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

    .movie-cover-wrapper:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .movie-cover-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
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
        .movie-form-grid {
            grid-template-columns: 1fr;
        }
        .movie-cover-wrapper {
            max-width: 280px;
            margin: 0 auto;
        }
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>

<div class="movie-form-grid">
    <!-- Left Column: Image Upload -->
    <div class="form-left-column">
        <div class="movie-cover-wrapper" id="cover-wrapper">
            <?php 
                $currentImage = $movie ? $movie->getImagePath() : '';
                // Treat the default placeholder as "no image" so the upload prompt shows
                $hasImage = !empty($currentImage) && $currentImage !== 'images/bez_plakatu.png';
            ?>
            
            <img id="preview-image" src="<?= $hasImage ? $currentImage : '' ?>" alt="Okładka" style="<?= $hasImage ? 'display:block' : 'display:none' ?>">
            
            <div class="cover-placeholder" id="cover-placeholder" style="<?= $hasImage ? 'display:none' : 'display:flex' ?>">
                <!-- Icon for upload -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Kliknij, aby dodać okładkę</span>
            </div>

            <input type="file" id="image_path" name="image_file" accept="image/*" class="hidden-file-input">
        </div>
        <?php if ($hasImage): ?>
            <input type="hidden" name="movie[image_path]" value="<?= $currentImage ?>">
        <?php endif; ?>
    </div>

    <!-- Right Column: Details -->
    <div class="form-right-column">
        <div class="form-group">
            <label for="title">Tytuł</label>
            <input type="text" placeholder="Wpisz tytuł filmu" id="title" name="movie[title]" value="<?= $movie ? htmlspecialchars($movie->getTitle() ?? '') : '' ?>" required>
            <?php if (!empty($errors['title'])): ?>
                <div style="color: red; font-size: 0.9em; margin-top: 5px;"><?= $errors['title'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="year">Rok produkcji</label>
                <input type="number" placeholder="Wpisz rok produkcji" id="year" name="movie[year]" value="<?= $movie ? $movie->getYear() : '' ?>" required>
                 <?php if (!empty($errors['year'])): ?>
                    <div style="color: red; font-size: 0.9em; margin-top: 5px;"><?= $errors['year'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="duration">Czas trwania (minuty)</label>
                <input type="number" placeholder="Wpisz czas trwania" id="duration" name="movie[duration]" value="<?= $movie ? $movie->getDuration() : '' ?>" required>
                 <?php if (!empty($errors['duration'])): ?>
                    <div style="color: red; font-size: 0.9em; margin-top: 5px;"><?= $errors['duration'] ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="category">Kategoria</label>
            <select id="category" name="movie[cat_id]">
                <option value="">-- Wybierz kategorię --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->getId() ?>" <?= ($movie && $movie->getCatId() == $category->getId()) ? 'selected' : '' ?>>
                        <?= $category->getName() ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Opis</label>
            <textarea id="description" name="movie[description]" rows="5"><?= $movie ? htmlspecialchars($movie->getDescription() ?? '') : '' ?></textarea>
             <?php if (!empty($errors['description'])): ?>
                <div style="color: red; font-size: 0.9em; margin-top: 5px;"><?= $errors['description'] ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Dostępne na platformach</label>
            <div class="platforms-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; margin-top: 5px; background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 8px;">
                <?php 
                $selectedIds = [];
                if (isset($moviePlatforms)) {
                    foreach ($moviePlatforms as $mp) {
                        $selectedIds[] = $mp->getId();
                    }
                }
                ?>
                <?php if (isset($platforms)): ?>
                    <?php foreach ($platforms as $platform): 
                        $isChecked = in_array($platform->getId(), $selectedIds) ? 'checked' : '';
                    ?>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal; font-size: 0.9em;">
                            <input type="checkbox" name="movie[platforms][]" value="<?= $platform->getId() ?>" <?= $isChecked ?>>
                            <?= htmlspecialchars($platform->getName()) ?>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-group submit-container">
            <input type="submit" value="Zapisz">
        </div>
    </div>
</div>

<script>
    document.getElementById('image_path').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview-image');
                const placeholder = document.getElementById('cover-placeholder');
                
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