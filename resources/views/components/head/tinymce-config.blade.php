<div>
    <script>
        window.loadTinyMCE = (function() {
            var promise;
            return function() {
                if (promise) return promise;
                promise = new Promise(function(resolve, reject) {
                    if (window.tinymce) { resolve(window.tinymce); return; }
                    var s = document.createElement('script');
                    s.src = "{{ asset('js/tinymce/tinymce.min.js') }}";
                    s.referrerPolicy = 'origin';
                    s.onload = function() { resolve(window.tinymce); };
                    s.onerror = function(e) { reject(e); };
                    document.head.appendChild(s);
                });
                return promise;
            };
        })();
    </script>
</div>
