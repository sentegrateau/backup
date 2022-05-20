<!-- Modal -->
<div class="modal order-cancel fade" id="close-ticket-modal" tabindex="-1" role="dialog" aria-labelledby="close-ticket-modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

                <!--<h5 class="modal-title" id="close-ticket-modalLongTitle" >Close Ticket</h5>-->
                <button  type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            <div class="modal-body">
			<div class="pop-icon">
			 <img src="{{asset('front/images/mailicon.png')}}">
			 </div>
               <h3> Are you sure, you want to close this ticket?</h3>
            </div>
            <div class="modal-footer-bottom">
                <button type="button" class="btn btn-secondary Cancel-pop" data-dismiss="modal">Cancel</button>
                <a href="" class="btn btn-primary btn-modal-close-ticket yes">Yes</a>
            </div>
        </div>
    </div>
</div>
