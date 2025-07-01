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
            <a class="navbar-brand" href="#">Sargent Dispatch</a>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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