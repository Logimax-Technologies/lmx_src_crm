<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logimax Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(to right, #e6f0ff, #f4f8ff);
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 1100px;
      margin: 60px auto;
      text-align: center;
    }

    h1 {
      color: #2c3e50;
      margin-bottom: 40px;
    }

    .cards {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 25px;
    }

    .card {
      background: linear-gradient(145deg, #ffffff, #f4f6fa);
      border-radius: 16px;
      padding: 30px 20px;
      width: 240px;
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
      transition: transform 0.2s ease-in-out, box-shadow 0.2s;
      text-decoration: none;
      color: #333;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }

    .card i {
      font-size: 38px;
      color: #4285f4;
      margin-bottom: 12px;
    }

    .card h2 {
      margin: 10px 0 6px;
      font-size: 20px;
      color: #2c3e50;
    }

    .card p {
      font-size: 14px;
      color: #666;
      margin: 0;
    }

    @media (max-width: 768px) {
      .card {
        width: 90%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Welcome to Logimax Dashboard</h1>
    <div class="cards">

      <form action="https://winjewel.mohanlaljewellers.in/winjewel_livetest/winjewel_api/index.php/Customer_api/crm_login" method="POST" target="_blank" style="display:inline;">
        <input type="hidden" name="user_id" value="12345">
        <input type="hidden" name="session_token" value="abcde12345">
        
        <button type="submit" class="card">
          <i class="fas fa-user-cog"></i>
          <h2>CRM</h2>
          <p>Manage leads, contacts, and customer interactions.</p>
        </button>
      </form>


      <form action="http://127.0.0.1:8000/crm_login" method="POST" target="_blank" style="display:inline;">
        <button type="submit" class="card">
          <i class="fas fa-user-cog"></i>
          <h2>Retail</h2>
          <p>Point of Sale & inventory for retail stores.</p>
        </button>
      </form>

      <a href="http://wholesale.makingminds.in" class="card" target="_blank">
        <i class="fas fa-boxes"></i>
        <h2>Wholesale</h2>
        <p>Bulk ordering and wholesale business tools.</p>
      </a>

      <a href="http://bullion.makingminds.in" class="card" target="_blank">
        <i class="fas fa-coins"></i>
        <h2>Bullion</h2>
        <p>Gold and silver trading platform and pricing tools.</p>
      </a>
       <a href="http://bullion.makingminds.in" class="card" target="_blank">
        <i class="fas fa-coins"></i>
        <h2>Win Jewel</h2>
        <p>Gold and silver trading platform and pricing tools.</p>
      </a>

    </div>
  </div>
</body>
</html>
