<?php
echo "<div class='list-item__listing'>
          <img 
              src='https://res.cloudinary.com/deb6r2y8g/image/upload/v1670567692/44310989._UY500_SS500__cxo78c.jpg'
          >
          <button data-key='$listing_id'>Edit</button>
          <div class='list-item__listing-info listing--$listing_id'>
              <p class='list-item__listing-user' data-key='$userId'>
              $username ($user_rating)</p>
              <p>$city, $state</p>
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

              <form id='edit-form' class='listing-edit'>
                  <input type='hidden' name='listing_id' value='$listing_id'>
                  <label for='city'>City:</label>
                  <input name='city' value='$city'><br>

                  <label for='state'>State:</label>
                  <input name='state' value='$state'><br>

                  <label for='condition'>Condition:</label>
                  <input name='condition' value='$condition'><br>

                  <label for='price'>Price:</label>
                  <input name='price' value='$price'><br>

                  <label for='description'>Description:</label><br>
                  <textarea name='description'>$description</textarea><br>
                  <input type='submit' value='Submit'>
                </form>
          </div>
      </div>";
?>