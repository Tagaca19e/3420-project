<?php
echo "<div class='list-item__listing'>
          <img 
              class='list-item__image'
              src='$photo_source'
          >
          <p 
              class='list-item__listing-user'
              data-key='$user_id'  
          >
          <a style='color: #4169e1; text-decoration: underline;'>$username</a> ($user_rating)</p>
          <p>$city, $state</p>
          <p>Qty: $quantity</p>
          <p>$condition: $price</p>
          <p>$description</p>
      </div>";
?>