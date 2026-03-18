<div id="questionRequiredAlertModal" class="modal" role="dialog">
    <div class="modal-dialog modal-alert">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>{{ tr('You must answer the question:') }}</h4>
                <p>{{$question->title}}</p>
                <button type="button" class="btn btn-info btn-wide" data-dismiss="modal">{{ tr('OK') }}</button>
            </div>
        </div>
    </div>
</div>
