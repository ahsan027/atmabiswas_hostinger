  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <div class="join-family">
      <h2>JOIN THE WORLD'S BIGGEST FAMILY</h2>
      <div class="family-options">
          <div class="option">
              <a href="notice.php">
                  <i class="fa-solid fa-handshake"></i>
                  <p>PARTNER WITH US</p>
              </a>
          </div>
          <div class="option">
              <a href="career.php">
                  <i class="fa-solid fa-suitcase"></i>
                  <p>CAREER</p>
              </a>

          </div>
          <div class="option">
              <a href="career.php">
                  <i class="fa-solid fa-school"></i>
                  <p>INTERNSHIP</p>
              </a>
          </div>
          <div class="option">
              <a href="contact.php">
                  <i class="fa-solid fa-location-dot"></i>
                  <p>VISIT US</p>
              </a>
          </div>
      </div>
  </div>
  <script src="script.js"></script>
  <style>
      .join-family {
          text-align: center;
          padding: 50px 20px;
          font-family: Arial, sans-serif;
      }

      .join-family h2 {
          font-size: 2em;
          font-weight: bold;
          color: #0073e6;
          margin-bottom: 40px;
      }

      .family-options {
          display: flex;
          justify-content: center;
          align-items: center;
          gap: 50px;
          flex-wrap: wrap;
      }

      .family-options a {
          display: flex;
          justify-content: center;
          align-items: center;
          text-decoration: none;
          gap: 0.5rem;
      }

      .option {
          display: flex;
          align-items: center;
          gap: 10px;
          cursor: pointer;
          transition: transform 0.3s ease;
      }

      .option:hover {
          transform: scale(1.1);
      }

      .option img {
          width: 60px;
          height: auto;
      }

      .option i {
          font-size: 2rem;
          color: #0073e6;
      }

      .option p {
          font-size: 1.2em;
          color: #666;
      }

      @media (max-width: 768px) {
          .family-options {
              display: flex;
              align-items: center;
              justify-content: center;
              flex-direction: column;
              gap: 30px;
          }
      }
  </style>