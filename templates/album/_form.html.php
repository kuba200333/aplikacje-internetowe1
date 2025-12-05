<?php global $router;
/** @var \App\Model\Album $album */
?>
<table border="1" style="border-collapse: collapse; margin: 10px 0; width: 100%;">
    <tr>
        <td><strong>Title:</strong></td>
        <td><input type="text" id="title" name="title" value="<?= htmlspecialchars($album->getTitle() ?? '') ?>" required style="width: 100%;"></td>
    </tr>
    <tr>
        <td><strong>Artist:</strong></td>
        <td><input type="text" id="artist" name="artist" value="<?= htmlspecialchars($album->getArtist() ?? '') ?>" required style="width: 100%;"></td>
    </tr>
    <tr>
        <td><strong>Release Year:</strong></td>
        <td><input type="number" id="release_year" name="release_year" value="<?= htmlspecialchars($album->getReleaseYear() ?? '') ?>" required style="width: 100%;"></td>
    </tr>
    <tr>
        <td><strong>Genre:</strong></td>
        <td><input type="text" id="genre" name="genre" value="<?= htmlspecialchars($album->getGenre() ?? '') ?>" required style="width: 100%;"></td>
    </tr>
</table>
<div style="margin-top: 10px;">
    <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Save</button>
    <a href="<?= $router->generatePath('album-index') ?>" style="padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;">Cancel</a>
</div>
