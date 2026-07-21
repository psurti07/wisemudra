    <form action="{{$action}}" method="post" name="frm1">
        @csrf
        <p>... Please Wait ....</p>
        <input type="hidden" name="encData" value="{{ $data }}" id="frm11">
        <input type="hidden" name="clientCode" value="{{ $clientCode }}" id="frm22">
    </form>
