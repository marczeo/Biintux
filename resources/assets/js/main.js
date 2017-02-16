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
    html.push( 
        '<form method="POST" action="'+ controller+'/'+row.id+'" class="form-inline" >' +
            '<input name="_method" type="hidden" value="DELETE">'+
            '<input name="_token" type="hidden" value="'+csrf_token+'">'+
            '<a href="'+controller+'/editar/'+row.id+'" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> '+
            '<button class="btn btn-xs btn-danger" type="submit" ><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'+ 
        '</form>'
    );
    html.push('</div>');
    return html.join('');
}
function formaterColumnColor(value, row, index, field) {
  return {
    css: {"background-color": row.color}
  };
}