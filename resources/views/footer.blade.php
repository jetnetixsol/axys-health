</div>

<!-- end page container -->

<!-- end footer -->
</div>



<!-- start js include path -->
<script src="{{ asset('assets/bundles/popper/popper.js') }}"></script>
<script src="{{ asset('assets/bundles/jquery-blockUI/jquery.blockui.min.js') }}"></script>
<script src="{{ asset('assets/bundles/jquery.slimscroll/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('assets/bundles/feather/feather.min.js') }}"></script>

<!-- bootstrap -->
<script src="{{ asset('assets/bundles/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/bundles/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

<!-- counterup -->
<script src="{{ asset('assets/bundles/counterup/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('assets/bundles/counterup/jquery.counterup.min.js') }}"></script>

<!-- Common js-->
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
<script src="{{ asset('assets/js/theme-color.js') }}"></script>
<!-- material -->
<script src="{{ asset('assets/bundles/material/material.min.js') }}"></script>
<!-- end js include path -->

<script>
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)

})
var popover = new bootstrap.Popover(document.querySelector('.popover-dismiss'), {
  trigger: 'hover'
})


    function onlyNumber(evt) {
        var theEvent = evt || window.event;
        // Handle pas   
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }
</script>
</body>

</html>