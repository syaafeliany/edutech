<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Teachers</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

   <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         font-family: 'Poppins', sans-serif;
         background: linear-gradient(135deg, #0a2a56 0%, #2978e0 100%);
         min-height: 100vh;
         display: flex;
         flex-direction: column;
      }

      main {
         flex: 1;
         display: flex;
         justify-content: center;
         align-items: flex-start;
         padding: 2rem 1rem;
         width: 100%;
      }

      .teachers {
         width: 100%;
         max-width: 1200px;
      }

      .heading {
         text-align: center;
         color: #fff;
         font-size: 2.5rem;
         margin-bottom: 1.5rem;
      }

      .search-tutor {
         display: flex;
         justify-content: center;
         margin-bottom: 2rem;
      }

      .search-tutor input {
         padding: 0.6rem 1rem;
         border: none;
         border-radius: 5px 0 0 5px;
         outline: none;
         width: 250px;
      }

      .search-tutor button {
         padding: 0.6rem 1rem;
         background-color: #fff;
         color: #2978e0;
         border: none;
         border-radius: 0 5px 5px 0;
         cursor: pointer;
      }

      .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
         gap: 1.5rem;
      }

      .box {
         background: #fff;
         padding: 1rem;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         display: flex;
         flex-direction: column;
         gap: 0.5rem;
      }

      .box.offer {
         background-color: #2978e0;
         color: #fff;
         text-align: center;
      }

      .box.offer h3 {
         margin-bottom: 0.5rem;
      }

      .box .tutor {
         display: flex;
         align-items: center;
         gap: 1rem;
         margin-bottom: 0.5rem;
      }

      .box .tutor img {
         width: 50px;
         height: 50px;
         border-radius: 50%;
         object-fit: cover;
      }

      .box p {
         margin: 0.2rem 0;
      }

      .inline-btn {
         display: inline-block;
         padding: 0.5rem 1rem;
         background-color: #4496ad;
         color: white;
         text-decoration: none;
         border-radius: 5px;
         text-align: center;
         margin-top: auto;
         cursor: pointer;
         border: none;
      }

      .empty {
         text-align: center;
         color:#4496ad;
         font-size: 1.8rem;
         grid-column: 1 / -1;
      }

      @media (max-width: 768px) {
         .heading {
            font-size: 2rem;
         }

         .search-tutor input {
            width: 180px;
         }
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<main>
   <section class="teachers">

      <h1 class="heading">Expert Tutors</h1>

      <form action="search_tutor.php" method="post" class="search-tutor">
         <input type="text" name="search_tutor" maxlength="100" placeholder="Search tutor..." required>
         <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
      </form>

      <div class="box-container">

         <div class="box offer">
            <h3>Become a Tutor</h3>
            <p>Join our platform and start teaching today!</p>
            <a href="admin/register.php" class="inline-btn">Get Started</a>
         </div>

         <?php
         $select_tutors = $conn->prepare("SELECT * FROM `tutors`");
         $select_tutors->execute();
         if ($select_tutors->rowCount() > 0) {
            while ($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)) {
               $tutor_id = $fetch_tutor['id'];

               $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
               $count_playlists->execute([$tutor_id]);
               $total_playlists = $count_playlists->rowCount();

               $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
               $count_contents->execute([$tutor_id]);
               $total_contents = $count_contents->rowCount();

               $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
               $count_likes->execute([$tutor_id]);
               $total_likes = $count_likes->rowCount();

               $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
               $count_comments->execute([$tutor_id]);
               $total_comments = $count_comments->rowCount();
         ?>
         <div class="box">
            <div class="tutor">
               <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="Tutor">
               <div>
                  <h3><?= $fetch_tutor['name']; ?></h3>
                  <span><?= $fetch_tutor['profession']; ?></span>
               </div>
            </div>
            <p>Playlists: <span><?= $total_playlists; ?></span></p>
            <p>Total Videos: <span><?= $total_contents; ?></span></p>
            <p>Total Likes: <span><?= $total_likes; ?></span></p>
            <p>Total Comments: <span><?= $total_comments; ?></span></p>
            <form action="tutor_profile.php" method="post">
               <input type="hidden" name="tutor_email" value="<?= $fetch_tutor['email']; ?>">
               <input type="submit" value="View Profile" name="tutor_fetch" class="inline-btn">
            </form>
         </div>
         <?php
            }
         } else {
            echo '<p class="empty">No tutors found!</p>';
         }
         ?>

      </div>
   </section>
</main>

<?php include 'components/footer.php'; ?>  

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>
