

var KSortable = Object.extend(Sortable, {
  serialize: function(element) {
    var element = $(element);
    var sortableOptions = this.options(element);
    var options = Object.extend({
      tag:  sortableOptions.tag,
      only: sortableOptions.only,
      name: element.id
    }, arguments[1] || {});
    
    var items = $(element).childNodes;
    var queryComponents = new Array();
    
    for(var i=0; i<items.length; i++)
      if(items[i].tagName && items[i].tagName==options.tag.toUpperCase() &&
        (!options.only || (Element.Class.has(items[i], options.only))))
        queryComponents.push(
          encodeURIComponent(options.name) + "[]=" + 
          encodeURIComponent(items[i].id) );

    return queryComponents.join("&");
  },
  
  dropAll: function(element) {
    var element = $(element);
    var sortableOptions = this.options(element);
    var options = Object.extend({
      tag:  sortableOptions.tag,
      only: sortableOptions.only,
      name: element.id
    }, arguments[1] || {});
    
    var items = $(element).childNodes;
    var queryComponents = new Array();
    
    for(var i=0; i<items.length; i++)
    {
      if(items[i].tagName && items[i].tagName==options.tag.toUpperCase() &&
        ( !options.only || (Element.Class.has(items[i], options.only)) ) )
      {
           Effect.Fade( items[i].id );
      }
    }
  }
});

