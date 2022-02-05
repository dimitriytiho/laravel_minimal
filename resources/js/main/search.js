const searchUrl = '/search-js',
    searchClass = '.search_js',
    searchInput = searchClass + '__input',
    searchChild = searchClass + '__child'

$(document).on('keyup', searchInput, function () {
    var self = $(this),
        query = self.val(),
        length = query.length,
        child = self.closest(searchClass).find(searchChild)

    if (length > 0) {
        $.ajax({
            type: 'POST',
            url: searchUrl,
            data: {
                _token,
                query
            },
            success: function(res) {
                if (res) {
                    child.html(res).addClass('active')
                }
            }
        })
    }
})
