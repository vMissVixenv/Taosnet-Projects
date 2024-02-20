<script>
function createPingResultElement(ip, status, responseTime) {
    // Create elements
    const container = document.createElement('div');
    const ipElement = document.createElement('p');
    const statusElement = document.createElement('p');
    const responseTimeElement = document.createElement('p');
    // Set content
    ipElement.textContent = IP: ${ip};
    statusElement.textContent = Status: ${status};
    responseTimeElement.textContent = Response Time: ${responseTime}ms;
    // Append elements to container
    container.appendChild(ipElement);
    container.appendChild(statusElement);
    container.appendChild(responseTimeElement);
    return container;
}
function pingDevice(event) {
    let dialog = document.getElementById('errorMessage');
dialog.textContent = ''; /* remove all existing child elements from dialog */
    event.preventDefault();
    let routerIP = event.target.getAttribute('data-router');
    let smIP = event.target.getAttribute('data-sm');
    // Make API call to ping.php for routerIP
    fetch('/api/v2/ping/' + routerIP)
        .then(response => response.json())
        .then(data => {
            // Create and display ping result element
            const pingResultElement = createPingResultElement(data.ip, data.status, data.response_time);
            dialog.appendChild(pingResultElement);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error fetching data. Please try again.');
        });
    // separate call for smIP
    if (smIP) {
        fetch('/api/v2/ping/' + smIP)
            .then(response => response.json())
            .then(data => {
                // Create and display ping result element
                const pingResultElement = createPingResultElement(data.ip, data.status, data.response_time);
               dialog.appendChild(pingResultElement);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching data. Please try again.');
                dialog.showModal();
            });
    }
}
</script>
</body>
</html>
