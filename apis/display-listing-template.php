<?php
session_start();
include "../snippets/data-encoder.php";
?>
<div class="list-item__listing list-item__listing--create">
    <div class="list-item__listing-info">
        <button class="listing-form__image-upload">Upload Image</button>
        <p></p>
        <form class="listing-create listing-form">
            <input type="hidden" name="user_id" value="<?= encode($_SESSION["user_id"]) ?>">
            <input type="hidden" name="photo_source" value="">
            <input type="hidden" name="start_date" value="<?= date("Y-m-d") ?>">
            <label for="city">City:</label>
            <input name="city"><br>

            <label for="state">State:</label>
            <input name="state"><br>

            <label for="condition">Condition:</label>
            <input name="condition"><br>

            <label for="price">Price:</label>
            <input name="price"><br>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity"><br>

            <label for="description">Description:</label><br>
            <textarea name="description"></textarea><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</div>