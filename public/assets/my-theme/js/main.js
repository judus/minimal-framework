var tipps = [
	[3, 5, 6, 2, 7, 9],
	[10, 4, 6, 2, 7, 11],
	[10, 5, 4, 2, 7, 17],
	[10, 3, 6, 9, 7, 17],
	[10, 5, 6, 2, 7, 17],
	[10, 5, 6, 2, 7, 11],
	[3, 5, 6, 9, 7, 11],
]

var r = [10, 5,  6, 2, 7];
var match = [];
console.log(tipps);

	tipps.forEach(function(ti, tKey) {
		console.log('Looking into tipps '+tKey);
		var tiMatch = 0;

		ti.forEach(function(ni, tiKey) {

			r.forEach(function(ri, rKey) {

				if (ri == ni) {
					tiMatch++;
					console.log('ri matches ni:'+ri+' : '+ni + ' match count: ' + tiMatch);

				}

			})

		});
		if (tiMatch == r.length) {
			match[tKey] = ti;
		}

});

console.log(match);
