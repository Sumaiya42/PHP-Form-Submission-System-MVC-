        </div>
    </div>
    <script>
        // Global JS for AJAX
        function ajax(url, method, data, successCallback, errorCallback) {
            const xhr = new XMLHttpRequest();
            xhr.open(method, url, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            successCallback(response);
                        } catch (e) {
                            errorCallback({ success: false, message: 'Invalid JSON response.' });
                        }
                    } else {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorCallback(response);
                        } catch (e) {
                            errorCallback({ success: false, message: 'Server error: ' + xhr.status });
                        }
                    }
                }
            };
            xhr.send(JSON.stringify(data));
        }
    </script>
</body>
</html>
