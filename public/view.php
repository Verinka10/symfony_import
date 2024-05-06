<html>
<head>
<script type="text/javascript">

		var request = function(uri, opts) {
				var g = new XMLHttpRequest;
				var saccess = false;
				opts = opts || {};
				opts.type = opts.type || 'GET';

				g.onreadystatechange = function() {
					if (g.readyState == 4) {
						if (g.status == 200) {
							saccess = true;
							opts.fun && opts.fun.call(this, g.responseText, g);
						}
					}
				}

				g.open(opts.type, uri, true);
				g.send(opts.data);
				return g;
			}
	
	window.onload = function() {
		document.querySelector("#get-balance").onclick = function() {
				var id = document.querySelector("#users").value;
				request('balance/?id=' + id, {fun: function(data) {
					var balance = JSON.parse(data);   
					var table = document.querySelector("#content table");
					table.innerHTML = '<tbody><th>date</th><th>sum</th><th>transactions</th></tbody>';
					for (var i in balance) {
						var row = table.insertRow(1);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        cell1.innerHTML = balance[i].date;
                        cell2.innerHTML = balance[i].sum;
                        cell3.innerHTML = balance[i].count;
					} 
			 }})
	    }
	    
	    // load users
	    request('users', {fun: function(data) {
			var users = JSON.parse(data);   	
			var select = document.querySelector("#users");
			select.innerHTML = '';
			for (var i in users) {
    			var opt = document.createElement("option");
                opt.value = i;
                opt.text = users[i];
                select.add(opt);
            }
		}})
	}
</script>
</head>
<body>
	<h2>Get balance</h2>
	<hr>
	<select id="users">	</select>
	<button id="get-balance">get-balance</button>
	<div id="content">
		<table>
		</table>
	</div>
</body>
</html>



