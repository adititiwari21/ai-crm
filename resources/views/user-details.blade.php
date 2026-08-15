<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI CRM - User Details</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #090d18;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .container {
            width: 500px;
            padding: 35px;
            border-radius: 20px;
            background: #111827;
            border: 1px solid #263452;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #8d9ab5;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #cbd5e1;
        }

        input,
        textarea {
            width: 100%;
            padding: 13px;
            border-radius: 10px;
            border: 1px solid #2d3b58;
            background: #0b1220;
            color: white;
            outline: none;
        }

        input:focus,
        textarea:focus {
            border-color: #667eea;
        }

        textarea {
            resize: vertical;
            min-height: 90px;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 5px;
        }

        button:hover {
            opacity: 0.9;
        }

        .scrape-button {
            background: linear-gradient(135deg, #00b09b, #96c93d);
            margin-top: 10px;
        }

        .result-box {
            display: none;
            margin-top: 25px;
            padding: 20px;
            border-radius: 14px;
            background: #0b1220;
            border: 1px solid #263452;
        }

        .result-box h2 {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .result-item {
            margin-bottom: 15px;
        }

        .result-label {
            color: #8d9ab5;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .result-value {
            color: #e5e7eb;
            word-break: break-word;
        }

        .result-value ul {
            padding-left: 20px;
            margin: 0;
        }

        .result-value li {
            margin-bottom: 6px;
        }

        .loading {
            color: #a5b4fc;
            text-align: center;
        }

        .error {
            color: #f87171;
            line-height: 1.6;
        }

        .debug-box {
            margin-top: 15px;
            padding: 12px;
            border-radius: 8px;
            background: #070b14;
            border: 1px solid #293653;
            font-size: 12px;
            color: #cbd5e1;
            overflow-x: auto;
        }

        .debug-box pre {
            white-space: pre-wrap;
            word-break: break-word;
            margin-top: 5px;
        }

        .success-message {
            margin-top: 12px;
            color: #4ade80;
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Tell us about yourself</h1>

    <p class="subtitle">
        Enter your details to personalize your CRM experience.
    </p>

    <form method="POST" action="/user-details">

        @csrf

        <div class="form-group">
            <label>Full Name</label>

            <input
                type="text"
                name="name"
                placeholder="Enter your name"
                required
            >
        </div>

        <div class="form-group">
            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >
        </div>

        <div class="form-group">
            <label>Phone</label>

            <input
                type="text"
                name="phone"
                placeholder="Enter your phone number"
            >
        </div>

        <div class="form-group">
            <label>Company</label>

            <input
                type="text"
                name="company"
                placeholder="Enter company name"
            >
        </div>

        <div class="form-group">
            <label>Company Website</label>

            <input
                type="url"
                id="website"
                name="website"
                placeholder="https://example.com"
                required
            >
        </div>

        <!-- Hidden analysis fields -->
        <input type="hidden" name="website_title" id="website_title">
        <input type="hidden" name="website_description" id="website_description">
        <input type="hidden" name="website_headings" id="website_headings">

        <button
            type="button"
            class="scrape-button"
            onclick="scrapeCompany()"
        >
            🔍 Analyze Company Website
        </button>

        <div class="form-group" style="margin-top: 20px;">

            <label>Requirements</label>

            <textarea
                name="requirements"
                placeholder="Tell us what you need..."
            ></textarea>

        </div>

        <button type="submit">
            Save Details
        </button>

        <div id="saveMessage" class="success-message"></div>

    </form>

    <div id="resultBox" class="result-box">

        <h2>Company Analysis</h2>

        <div id="resultContent"></div>

    </div>

</div>


<script>

async function scrapeCompany() {

    const website =
        document.getElementById("website").value.trim();

    const resultBox =
        document.getElementById("resultBox");

    const resultContent =
        document.getElementById("resultContent");

    const saveMessage =
        document.getElementById("saveMessage");


    saveMessage.innerHTML = "";


    if (!website) {

        alert("Please enter a company website first.");

        return;
    }


    resultBox.style.display = "block";

    resultContent.innerHTML = `
        <div class="loading">
            🔄 Analyzing company website...
        </div>
    `;


    try {

        const csrfToken =
            document.querySelector(
                'input[name="_token"]'
            ).value;


        const response = await fetch(
            "/scrape-company",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },

                body: JSON.stringify({
                    website: website
                })
            }
        );


        const responseText =
            await response.text();


        let data;


        try {

            data = JSON.parse(responseText);

        } catch (error) {

            resultContent.innerHTML = `
                <div class="error">
                    ❌ Server returned an invalid response.
                </div>
            `;

            return;
        }


        if (!data.success) {

            resultContent.innerHTML = `
                <div class="error">
                    ❌ Scraping failed.

                    <div class="debug-box">

                        <strong>Error:</strong>

                        <pre>${data.error || "Unknown error"}</pre>

                        <strong>Output:</strong>

                        <pre>${data.output || "No output"}</pre>

                    </div>
                </div>
            `;

            return;
        }


        const output = data.output || "";

        const lines =
            output.split(/\r?\n/);


        let title = "Not found";

        let description = "Not found";

        let headings = [];


        lines.forEach(line => {

            line = line.trim();


            if (line.startsWith("Title:")) {

                title =
                    line.replace("Title:", "").trim();
            }


            if (line.startsWith("Description:")) {

                description =
                    line.replace("Description:", "").trim();
            }


            if (line.startsWith("-")) {

                const heading =
                    line.replace(/^-+\s*/, "").trim();

                if (
                    heading !== "" &&
                    !/^-+$/.test(heading)
                ) {

                    headings.push(heading);
                }
            }

        });


        headings = [
            ...new Set(headings)
        ];


        // SAVE ANALYSIS IN HIDDEN FORM FIELDS

        document.getElementById(
            "website_title"
        ).value = title;


        document.getElementById(
            "website_description"
        ).value = description;


        document.getElementById(
            "website_headings"
        ).value = headings.join("\n");


        const headingHTML =
            headings.length

            ? headings
                .map(
                    heading =>
                        `<li>${heading}</li>`
                )
                .join("")

            : "<li>No headings found</li>";


        resultContent.innerHTML = `

            <div class="result-item">

                <div class="result-label">
                    Website Title
                </div>

                <div class="result-value">
                    ${title}
                </div>

            </div>


            <div class="result-item">

                <div class="result-label">
                    Description
                </div>

                <div class="result-value">
                    ${description}
                </div>

            </div>


            <div class="result-item">

                <div class="result-label">
                    Headings
                </div>

                <div class="result-value">

                    <ul>
                        ${headingHTML}
                    </ul>

                </div>

            </div>

        `;

        saveMessage.innerHTML =
            "✓ Company analysis ready. Click Save Details to save it.";

    }

    catch (error) {

        resultContent.innerHTML = `

            <div class="error">

                ❌ Unable to connect to scraper.

                <div class="debug-box">

                    ${error.message}

                </div>

            </div>

        `;

    }

}

</script>

</body>
</html>