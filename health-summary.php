<?php
if (!defined('ABSPATH')) {
	exit;
}

$username = "zxl101";
$password = "S079z079";
$host = "mytestdb.cjkcq2pcruvk.us-east-2.rds.amazonaws.com";
$database = "iter2";

$connect = mysqli_connect($host, $username, $password, $database);

$myquery5 = "select count(*)/pop_no *100000 as hospital_no_per, count(*) as hospital_no, c.city_id, city_name
            from hospital as h join city as c on h.city_id = c.city_id
            join pop_total as pt on pt.city_id  = c.city_id
            group by h.city_id;";

$query5 = mysqli_query($connect, $myquery5);

$hs    = array();
while ( $row = mysqli_fetch_assoc( $query5 ) ) {
	$element = array();
	$element['hospital_per'] = $row['hospital_no_per'];
	$element['hospital_no'] = $row['hospital_no'];
	$element['city_id'] = $row['city_id'];
	$element['city_name'] = $row['city_name'];
	$hs[] = $element;
}
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

	<script src="http://d3js.org/d3.v3.min.js"></script>
	<script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>
	<script src="https://d3js.org/d3-axis.v1.min.js"></script>
	<script>

        function makeFirstGraph_hos() {

            d3.select(".hos_figure_1").selectAll("*").remove();

            var hs_data = <?php echo json_encode($hs); ?>;
            console.log(hs_data)
						var margin1 = {top: 55, right: 50, bottom: 55, left: 80},
								width1 = 640 - margin1.left - margin1.right,
								height1 = 350 - margin1.top - margin1.bottom;

						hs_data.sort(function(a, b) {
												return d3.descending(Number(a.hospital_no), Number(b.hospital_no))
											})

            var svg1 = d3.select(".hos_figure_1").append("svg")
                .attr("class", "bar_chart")
                .attr("width", width1 + margin1.left + margin1.right)
                .attr("height", height1 + margin1.top + margin1.bottom)
                .append("g")
                .attr("transform", "translate(" + margin1.left + "," + margin1.top + ")");
            var tip4 = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0])
                .html(function(d) {
                    return "<strong>Number of Hospitals:</strong> <span style='color:mediumpurple'>" + d.hospital_no + "</span>";
                })
            svg1.call(tip4);
            var x1 = d3.scale.ordinal()
                .domain(hs_data.map(function (d) {
                    return d.city_name;
                }))
                .rangeBands([0, width1]);
            var xAxis1 = d3.svg.axis()
                .scale(x1)
                .orient("bottom");
            var y1 = d3.scale.linear()
                .domain([0,6])
                .range([height1, 0]);
            // console.log(d3.max(sc_data,function(d){retrun d.school_no}))
            var yAxis1 = d3.svg.axis()
                .scale(y1)
                .orient("left");
            svg1.append("g")
                .attr("class", "x1 axis")
                .attr("transform", "translate(0," + height1 + ")")
                .call(xAxis1)
                .selectAll("text")
                .style("font-size", "10px")
                .style("text-anchor", "end")
                .attr("dx", "1em")
                .attr("dy", "0.75em")
                .attr("transform", "rotate(-30)");
            svg1.append("g")
                .attr("class", "y1 axis")
                .call(yAxis1)
                .append("text")
                .attr("transform", "rotate(-90)")
                .attr("x", -45)
                .attr("y", -45)
                .attr("dy", ".71em")
                .style("text-anchor", "end")
                .style("font-size", "12px")
                .text("Number of Hospitals");
            svg1.selectAll("rectangle1")
                .data(hs_data)
                .enter()
                .append("rect")
                .attr("class", "rectangle1")
                .attr("width", width1 / hs_data.length - 20)
                .attr("height", function (d) {
                    return height1 - y1(d.hospital_no);
                })
                .attr("x", function (d, i) {
                    return (width1 / hs_data.length) * i + 10;
                })
                .attr("y", function (d) {
                    return y1(d.hospital_no);
                })
                // .attr("fill", function (d) {
                //     return color[num];
                // })
                .on('mouseover', tip4.show)
                .on('mouseout', tip4.hide)
                .attr("fill","#99CCFF")
                .style("margin-top", "10px")
                .append("title")
                .text(function (d) {
                    return d.city_name + " : " + d.hospital_no;
                });

            svg1.append("text")
                .attr("x", (width1 / 2))
                .attr("y", 0 - (margin1.top / 2))
                .attr("text-anchor", "middle")
                .style("font-size", "25px")
                // .style("text-decoration", "underline")
                .text("Number of Hospitals in Each City");
        }

        function makeSecondGraph_hos() {

            d3.select(".hos_figure_2").selectAll("*").remove();

            var hs_data = <?php echo json_encode($hs); ?>;
            // console.log(sc_data)
						var margin1 = {top: 55, right: 50, bottom: 55, left: 80},
								width1 = 640 - margin1.left - margin1.right,
								height1 = 350 - margin1.top - margin1.bottom;

						hs_data.sort(function(a, b) {
												return d3.descending(Number(a.hospital_per), Number(b.hospital_per))
											})

            var svg1 = d3.select(".hos_figure_2").append("svg")
                .attr("class", "bar_chart")
                .attr("width", width1 + margin1.left + margin1.right)
                .attr("height", height1 + margin1.top + margin1.bottom)
                .append("g")
                .attr("transform", "translate(" + margin1.left + "," + margin1.top + ")");

            var tip3 = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0])
                .html(function (d) {
                    return "<strong>Number of Hospitals:</strong> <span style='color:mediumpurple'>" + d.hospital_per + "</span>";
                });
            svg1.call(tip3);
            var x1 = d3.scale.ordinal()
                .domain(hs_data.map(function (d) {
                    return d.city_name;
                }))
                .rangeBands([0, width1]);
            var xAxis1 = d3.svg.axis()
                .scale(x1)
                .orient("bottom");
            var y1 = d3.scale.linear()
                .domain([0, d3.max(hs_data, function (d) {
                    return d.hospital_per;
                })])
                .range([height1, 0]);

            var yAxis1 = d3.svg.axis()
                .scale(y1)
                .orient("left");
            svg1.append("g")
                .attr("class", "x1 axis")
                .attr("transform", "translate(0," + height1 + ")")
                .call(xAxis1)
                .selectAll("text")
                .style("font-size", "10px")
                .style("text-anchor", "end")
                .attr("dx", "1em")
                .attr("dy", "0.75em")
                .attr("transform", "rotate(-30)");
            svg1.append("g")
                .attr("class", "y1 axis")
                .call(yAxis1)
                .append("text")
                .attr("transform", "rotate(-90)")
                .attr("x", -15)
                .attr("y", -45)
                .attr("dy", ".71em")
                .style("text-anchor", "end")
                .style("font-size", "12px")
                .text("Number of Hospitals Per 100,000 People");
            svg1.selectAll("rectangle1")
                .data(hs_data)
                .enter()
                .append("rect")
                .attr("class", "rectangle1")
                .attr("width", width1 / hs_data.length - 20)
                .attr("y", function (d) {
                    return y1(d.hospital_per);
                })
                .attr("height", function (d) {
                    return height1 - y1(d.hospital_per);
                })
                .attr("x", function (d, i) {
                    return (width1 / hs_data.length) * i + 10;
                })

                // .attr("fill", function (d) {
                //     return color[num];
                // })
                .on('mouseover', tip3.show)
                .on('mouseout', tip3.hide)
                .attr("fill", "#99CCFF")
                .style("margin-top", "10px")
                .append("title")
                .text(function (d) {
                    return d.city_name + " : " + d.hospital_per;
                });

            svg1.append("text")
                .attr("x", (width1 / 2))
                .attr("y", 0 - (margin1.top / 2))
                .attr("text-anchor", "middle")
                .style("font-size", "25px")
                // .style("text-decoration", "underline")
                .text("Number of Hospitals Per 100,000 People in Each City");
        }


        $(document).ready(function () {
            makeFirstGraph_hos();
            $('#hos_first_choice').on('click', function () {
                makeFirstGraph_hos();
                $('.hos_figure_2').hide();
                $('.hos_figure_1').fadeIn();
            });

            $('#hos_sec_choice').on('click', function () {
                makeSecondGraph_hos();
                $('.hos_figure_1').hide();
                $('.hos_figure_2').fadeIn();
            })
        })


	</script>
</head>
<body>

<form id="form_hos" class="btn-group_hea btn-group-toggle" data-toggle="buttons">
	<label class="btn_hea btn-secondary active">
		<input type="radio" name="controlType" checked id="hos_first_choice" style="background: skyblue">Number of Hospitals<br>
	</label><br>
	<label class="btn_hea btn-secondary">
		<input type="radio" name="controlType" id="hos_sec_choice" style="background: skyblue">Number of Hospitals per 100,000 people<br>
	</label>
</form>

<div class="hos_figure_1"></div>

<div class="hos_figure_2"></div>
</body>

</html>
