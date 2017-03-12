/*Functions for use bootstrap table*/
function queryParams() {
    return {
        type: 'owner',
        sort: 'updated',
        direction: 'desc',
        per_page: 100,
        page: 1
    };
}
function formaterColumnEditActions(value, row, index) {
    //console.log(this);
    var controller=this.field;
    var csrf_token=$('meta[name="csrf-token"]').attr('content');
    var html = [];
    html.push('<div class="btn-group">');
    html.push('<form method="POST" action="'+ controller+'/'+row.id+'" class="form-inline" >');
    html.push('<input name="_method" type="hidden" value="DELETE">');
    html.push('<input name="_token" type="hidden" value="'+csrf_token+'">');
    if(controller!="ciclovia" && controller!="route")
        html.push('<a href="'+controller+'/'+row.id+'/edit/" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> ');
    html.push('<button class="btn btn-xs btn-danger" type="submit" ><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>');
    html.push('</form>');
    html.push('</div>');
    return html.join('');
}
function formaterColumnColor(value, row, index, field) {
  return {
    css: {"background-color": row.color}
  };
}