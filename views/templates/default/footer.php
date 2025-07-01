<?php
/**
* Default Footer
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/
?>

    <nav class="navbar bg-dark fixed-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">UserCandy</a>
        </div>
    </nav>

    <script src="<?=SITE_URL?>templates/default/assets/js/popper.min.js"></script>
    <script src="<?=SITE_URL?>templates/default/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?=SITE_URL?>templates/default/assets/js/jquery-3.6.1.min.js"></script>
    <script src="<?=SITE_URL?>templates/default/assets/js/select2.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function(){
            $('#alertModal').modal('show');
        });
    </script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
        $(document).ready(function(){
          $(".single-select").select2();
        })
    </script>
  </body>
</html>