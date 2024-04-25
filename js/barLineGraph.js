document.addEventListener("DOMContentLoaded", function () {
  var myModal = new bootstrap.Modal("#updateGraphModal");

  function aggregateData(responseData) {
    const aggregatedData = responseData.reduce((acc, curr) => {
      const { callDate, rowCount, statusCount, closeCount } = curr;
      if (!acc[callDate]) {
        acc[callDate] = {
          callDate,
          rowCount: parseInt(rowCount),
          statusCount: parseInt(statusCount),
          closeCount: parseInt(closeCount),
          actualCount: parseInt(curr.actualCount) || 0, // Ensuring actualCount is valid
        };
      } else {
        acc[callDate].rowCount += parseInt(rowCount);
        acc[callDate].statusCount += parseInt(statusCount);
        acc[callDate].closeCount += parseInt(closeCount);
        acc[callDate].actualCount += parseInt(curr.actualCount) || 0; // Ensuring actualCount is valid
      }
      return acc;
    }, {});

    return Object.values(aggregatedData);
  }

  function fetchData() {
    var callDateStart = document.getElementById("callDateStart").value;
    var callDateEnd = document.getElementById("callDateEnd").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/graphData.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var requestData =
      "callDateStart=" +
      encodeURIComponent(callDateStart) +
      "&callDateEnd=" +
      encodeURIComponent(callDateEnd);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        if (xhr.status == 200) {
          try {
            var responseData = JSON.parse(xhr.responseText);
            console.log(responseData);
            // Check if responseData has the expected structure
            if (Array.isArray(responseData) && responseData.length > 0) {
              // Aggregate data
              var aggregatedData = aggregateData(responseData);

              // Extract data from aggregated array
              var labels = aggregatedData.map(function (item) {
                return item.callDate;
              });

              var barData = aggregatedData.map(function (item) {
                return item.rowCount;
              });

              var lineData = aggregatedData.map(function (item) {
                return item.statusCount;
              });

              var totalCallCount = aggregatedData.reduce(function (sum, item) {
                return sum + item.rowCount;
              }, 0);

              var totalActualCount = aggregatedData.reduce(function (
                sum,
                item
              ) {
                // Check if item.actualCount is defined and non-null
                if (
                  item.actualCount !== undefined &&
                  item.actualCount !== null &&
                  !Number.isNaN(item.actualCount)
                ) {
                  return sum + item.actualCount;
                }
                return sum;
              },
              0);

              // Calculate the sum of all closeCount values
              var totalCloseCount = aggregatedData.reduce(function (sum, item) {
                return sum + item.closeCount;
              }, 0);

              var conversionRate =
                totalActualCount > 0
                  ? (totalCloseCount / totalActualCount) * 100
                  : 0;

              // Update chart data
              barLineChart.data.labels = labels;
              barLineChart.data.datasets[0].data = barData;
              barLineChart.data.datasets[1].data = lineData;

              document.getElementById("callCountSpan").innerHTML =
                totalCallCount;
              document.getElementById("actualCountSpan").innerHTML =
                totalActualCount;
              document.getElementById("actualClosedCountSpan").innerHTML =
                totalCloseCount;
              document.getElementById("conversionSpan").innerHTML =
                conversionRate.toFixed(2) + "%";

              myModal.hide();
              barLineChart.update();
            } else {
              console.error("Invalid response format:", responseData);
            }
          } catch (error) {
            console.error("Error parsing response:", error);
          }
        } else {
          console.error("Error fetching data. Status:", xhr.status);
        }
      }
    };

    if (callDateStart && callDateEnd) {
      xhr.send(requestData);
    } else {
      xhr.send();
    }
  }

  // Add an event listener to the form
  var updateGraphForm = document.getElementById("updateGraphForm");
  updateGraphForm.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission
    fetchData(); // Call the fetchData function to update the chart
  });

  // Sample data for the initial chart
  var chartData = {
    labels: ["Label 1", "Label 2", "Label 3", "Label 4", "Label 5"],
    datasets: [
      {
        label: "Calls",
        backgroundColor: "rgba(75, 192, 192, 0.2)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 1,
        data: [5, 10, 15, 7, 20],
        type: "bar",
      },
      {
        label: "Closed Call",
        borderColor: "rgba(255, 99, 132, 1)",
        borderWidth: 2,
        fill: false,
        data: [10, 5, 8, 15, 12],
        type: "line",
      },
    ],
  };

  var ctx = document.getElementById("barLineChart").getContext("2d");
  var barLineChart = new Chart(ctx, {
    type: "bar",
    data: chartData,
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });

  // Fetch data initially
  fetchData();
});
