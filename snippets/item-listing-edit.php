<?php
echo "<div class='list-item__listing'>
          <div class='list-item__listing-info listing--$listing_id'>
              <img 
                  class='list-item__image'
                  src='$photo_source'
              >
              <button data-key='$listing_id'>Edit</button>
              <p class='list-item__listing-user' data-key='$userId'>
              $username ($user_rating)</p>
              <p>$city, $state</p>
              <p>Qty: $quantity</p>
              <p>$condition: $price</p>
              <p>$description</p>
          </div>

          <div class='list-item__listing-info--edit listing-edit--$listing_id'>
              <button 
                  class='listing-edit__delete'
                  data-key='$listing_id'
              >
              Delete
              </button>
              <button class='listing-form__image-upload'>Upload Image</button>
              <p></p>
              <form id='edit-form' class='listing-edit listing-form'>
                  <input type='hidden' name='listing_id' value='$listing_id'>
                  <input type='hidden' name='photo_source' value='$photo_source'>
                  <label for='end_date'>End date:</label>
                  <input name='end_date' value=''>

                  <label for='city'>City:</label>
                  <input name='city' value='$city'><br>

                  <label for='state'>State:</label>
                  <input name='state' value='$state'><br>

                  <label for='condition'>Condition:</label>
                  <input name='condition' value='$condition'><br>

                  <label for='price'>Price:</label>
                  <input name='price' value='$price'><br>

                  <label for='quantity'>Quantity:</label>
                  <input type='number' name='quantity' value='$quantity'><br>

                  <label for='description'>Description:</label><br>
                  <textarea name='description'>$description</textarea><br>
                  <input type='submit' value='Submit'>
                </form>

                <button 
                    class='listing-edit__cancel' 
                    data-key='$listing_id'
                >
                cancel</button>
          </div>
      </div>";
?>