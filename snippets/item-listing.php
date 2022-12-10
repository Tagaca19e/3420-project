<?php
echo "<div class='list-item__listing'>
          <img 
              src='https://res.cloudinary.com/deb6r2y8g/image/upload/v1670567692/44310989._UY500_SS500__cxo78c.jpg'
          >
          <p 
              class='list-item__listing-user'
              data-key='$user_id'  
          >
          $username ($userRating)</p>
          <p>$city, $state</p>
          <p>$condition: $price</p>
          <p>$description</p>
      </div>";
?>