$(".paginate-item").on('click', function(event) {
    const items = $(this).data('items');
    const url = window.location.href;
    const origin = window.location.origin;
    const current = url.split(origin);
    const newUrl = updateQueryStringParameter(current[1], 'items', items);
    window.location.href = newUrl;
});

function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}