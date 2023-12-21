<?php
session_start();
include("../bdd/bdd.php");
include("../class/user.php");

$login = $_SESSION['id_utilisateur'];
$isAdmin = $_SESSION['isAdmin'];

// Vérif si user connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('location: ../index.php');
    exit;
}

// User = non Admin = redirection
if ($_SESSION['isAdmin'] != 1) {
    header('location: 404.php'); 
    exit;
}

// Appel fonction ModifierUser
if (isset($_POST['modifier'])) {
    $loginToModify = $_POST['loginToModify'];
    $newLogin = $_POST['newLogin'];
    $newPasswd = $_POST['newPasswd'];
    User::ModifierUser($loginToModify, $newLogin, $newPasswd);
}

// Appel fonction SupprimerUser
if (isset($_POST['supprimer'])) {
    $loginToDelete = $_POST['loginToDelete'];
    User::SupprimerUser($loginToDelete);
}

// Appel fonction Deconnexion
if (isset($_POST['deconnexion'])) {
    User::Deconnexion(); 
    header('location: ../index.php'); 
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DMX - Espace Admin</title>

    <!-- Custom fonts for this template -->
    <link href="../assets//vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets//css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../assets//vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="acceuil.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">PROJET <sup>DMX</sup></div>
            </a>

            <!-- Nav Item - Accueil -->
            <li class="nav-item">
                <a class="nav-link" href="accueil.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Accueil</span></a>
            </li>

            <!-- Condition pour afficher la partie "Nav Item - Admin" uniquement si isAdmin est égal à 1 -->
            <?php if ($isAdmin == 1) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Espace Admin</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                <!-- On affiche le nom de l'user -->
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $login; ?></span>

                                <img class="img-profile rounded-circle" src="../assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="parametres.php">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Paramètres
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="post" action="">
                                    <button type="submit" name="deconnexion" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Mon espace Administrateur</h1>
                    <p class="mb-4">Vous avez la possibilité via cette page de pouvoir gérer tous les utilisateurs inscrit sur votre site.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Base_PROJET</h6>
                        </div>
                        <div class="card-body">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Liste des Utilisateurs</h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                    User::AfficherTableauUtilisateurs(); // Appel de la fonction pour afficher le tableau des utilisateurs
                                    ?>
                                </div>

                                <!-- Fenêtre pour modifier un user -->
                                <div class="modal fade" id="modifierModal" tabindex="-1" role="dialog" aria-labelledby="modifierModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modifierModalLabel">Modifier l'utilisateur</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="admin.php">
                                                    <div class="form-group">
                                                        <label for="newLogin">Nouveau Login :</label> <!-- Son nouveau Login -->
                                                        <input type="text" class="form-control" id="newLogin" name="newLogin" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="newPasswd">Nouveau Mot de passe :</label> <!-- Son nouveau password -->
                                                        <input type="password" class="form-control" id="newPasswd" name="newPasswd" required>
                                                    </div>
                                                    <input type="hidden" id="loginToModify" name="loginToModify">
                                                    <button type="submit" class="btn btn-primary" name="modifier">Modifier</button> <!-- Confirmation -->
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fenêtre pour supprimer un user -->
                                <div class="modal fade" id="supprimerModal" tabindex="-1" role="dialog" aria-labelledby="supprimerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="supprimerModalLabel">Confirmer la suppression</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer cet utilisateur ?
                                            </div>
                                            <div class="modal-footer">
                                                <form method="post" action="admin.php">
                                                    <input type="hidden" id="loginToDelete" name="loginToDelete">
                                                    <button type="submit" class="btn btn-danger" name="supprimer">Supprimer</button> <!-- Confirmation -->
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button> <!-- Annulation -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.container-fluid -->
                    </div>
                    <!-- End of Main Content -->

                    <!-- Footer -->
                    <footer class="sticky-footer bg-white">
                        <div class="container my-auto">
                            <div class="copyright text-center my-auto">
                                <span>Copyright &copy; PROJET DMX</span>
                            </div>
                        </div>
                    </footer>
                    <!-- End of Footer -->

                </div>
                <!-- End of Content Wrapper -->

            </div>
            <!-- End of Page Wrapper -->

            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

            <!-- Bootstrap core JavaScript-->
            <script src="../assets//vendor/jquery/jquery.min.js"></script>
            <script src="../assets//vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <!-- Core plugin JavaScript-->
            <script src="../assets//vendor/jquery-easing/jquery.easing.min.js"></script>

            <!-- Custom scripts for all pages-->
            <script src="../assets//js/sb-admin-2.min.js"></script>

            <!-- Page level plugins -->
            <script src="../assets//vendor/datatables/jquery.dataTables.min.js"></script>
            <script src="../assets//vendor/datatables/dataTables.bootstrap4.min.js"></script>

            <!-- Page level custom scripts -->
            <script src="../assets//js/demo/datatables-demo.js"></script>

</body>

</html>