<?php
include 'components/connect.php';

$user_id = $_COOKIE['user_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Courses | E-TECH</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

   <style>
      * {
         box-sizing: border-box;
         margin: 0;
         padding: 0;
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
         align-items: center;
         padding: 2rem;
      }

      .courses {
         width: 100%;
         max-width: 1200px;
      }

      .heading {
         text-align: center;
         font-size: 2.2rem;
         color: #fff;
         margin-bottom: 2rem;
      }

      .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
         gap: 2rem;
      }

      .box {
         background: #fff;
         border-radius: 10px;
         padding: 1rem;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      .box .tutor {
         display: flex;
         align-items: center;
         gap: 1rem;
         margin-bottom: 1rem;
      }

      .box .tutor img {
         width: 50px;
         height: 50px;
         object-fit: cover;
         border-radius: 50%;
      }

      .box .thumb {
         width: 100%;
         border-radius: 8px;
         margin-bottom: 1rem;
      }

      .inline-btn {
         display: inline-block;
         background: #2978e0;
         color: #fff;
         padding: 0.5rem 1rem;
         border-radius: 5px;
         text-decoration: none;
      }

      .empty {
         text-align: center;
         color:#4496ad;
         font-size: 1.8rem;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<main>
   <section class="courses">
      <h1 class="heading">All Courses</h1>

      <div class="box-container">
         <?php
            $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC");
            $select_courses->execute(['active']);
            if ($select_courses->rowCount() > 0) {
               while ($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)) {
                  $course_id = $fetch_course['id'];
                  $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                  $select_tutor->execute([$fetch_course['tutor_id']]);
                  $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="box">
            <div class="tutor">
               <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="Tutor">
               <div>
                  <h3><?= $fetch_tutor['name']; ?></h3>
                  <span><?= $fetch_course['date']; ?></span>
               </div>
            </div>
            <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="Course Thumbnail">
            <h3 class="title"><?= $fetch_course['title']; ?></h3>
            <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">View Playlist</a>
         </div>
         <?php
               }
            } else {
               echo '<p class="empty">No courses added yet!</p>';
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
