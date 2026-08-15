<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AI Assistant - AI CRM</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at 15% 10%, rgba(99,102,241,0.12), transparent 30%),
                radial-gradient(circle at 85% 80%, rgba(139,92,246,0.10), transparent 30%),
                #090d16;
            color: #f8fafc;
        }

        .container {
            max-width: 1050px;
            margin: auto;
            padding: 35px;
        }


        /* HEADER */

        .header {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #111827, #151b2b);
            border: 1px solid #263248;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 22px;
        }

        .header::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            right: -80px;
            top: -100px;
            background: rgba(99,102,241,0.12);
            border-radius: 50%;
        }

        .ai-title {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 1;
        }

        .ai-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            font-size: 25px;
            box-shadow: 0 10px 30px rgba(99,102,241,0.25);
        }

        .header h1 {
            font-size: 27px;
            margin-bottom: 5px;
        }

        .header p {
            color: #94a3b8;
            font-size: 13px;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 20px;
            color: #86efac;
            background: rgba(34,197,94,0.08);
            border: 1px solid rgba(34,197,94,0.15);
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 11px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #4ade80;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            color: #a5b4fc;
            text-decoration: none;
            font-size: 13px;
        }

        .back:hover {
            color: white;
        }


        /* CHAT BOX */

        .chat-box {
            background: #111827;
            border: 1px solid #1e293b;
            padding: 28px;
            border-radius: 18px;
        }

        .chat-heading {
            margin-bottom: 7px;
            font-size: 18px;
        }

        .chat-subtitle {
            color: #64748b;
            font-size: 12px;
            margin-bottom: 22px;
        }


        /* QUESTION */

        .question-box {
            display: flex;
            gap: 10px;
            background: #0b111d;
            border: 1px solid #253047;
            padding: 7px;
            border-radius: 12px;
        }

        input {
            flex: 1;
            padding: 13px 14px;
            border: none;
            outline: none;
            background: transparent;
            color: white;
            font-size: 14px;
        }

        input::placeholder {
            color: #64748b;
        }

        .ask-btn {
            padding: 12px 21px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(135deg, #6366f1, #7c3aed);
            color: white;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .ask-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 20px rgba(99,102,241,0.2);
        }


        /* ANSWER */

        .answer {
            margin-top: 22px;
            padding: 20px;
            background: rgba(99,102,241,0.07);
            border: 1px solid rgba(99,102,241,0.25);
            border-radius: 13px;
        }

        .answer-header {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #a5b4fc;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 11px;
        }

        .answer p {
            color: #cbd5e1;
            line-height: 1.7;
            font-size: 13px;
        }


        /* QUICK QUESTIONS */

        .examples {
            margin-top: 28px;
        }

        .examples h3 {
            font-size: 14px;
            margin-bottom: 12px;
        }

        .quick-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
        }

        .quick-btn {
            background: #0d1422;
            color: #94a3b8;
            border: 1px solid #253047;
            padding: 9px 13px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 11px;
            transition: 0.2s ease;
        }

        .quick-btn:hover {
            border-color: #6366f1;
            color: #c4b5fd;
            background: rgba(99,102,241,0.08);
        }


        /* STATS */

        .stats-title {
            margin-top: 30px;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .stat {
            background: #0d1422;
            border: 1px solid #1e293b;
            padding: 18px;
            border-radius: 12px;
            text-align: center;
            transition: 0.2s ease;
        }

        .stat:hover {
            transform: translateY(-2px);
            border-color: #334155;
        }

        .stat strong {
            display: block;
            font-size: 22px;
            margin-bottom: 7px;
        }

        .stat span {
            font-size: 11px;
            color: #64748b;
        }


        /* RESPONSIVE */

        @media(max-width: 700px) {

            .container {
                padding: 20px;
            }

            .question-box {
                flex-direction: column;
            }

            .ask-btn {
                width: 100%;
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

        }

    </style>

</head>


<body>

<div class="container">


    <!-- HEADER -->

    <div class="header">

        <div class="ai-title">

            <div class="ai-icon">
                🤖
            </div>

            <div>

                <h1>
                    AI CRM Assistant
                </h1>

                <p>
                    Intelligent insights from your CRM data
                </p>

            </div>

        </div>


        <div class="status">

            <span class="status-dot"></span>

            AI Assistant Online

        </div>


        <a
            href="{{ route('dashboard') }}"
            class="back"
        >
            ← Back to Dashboard
        </a>

    </div>



    <!-- CHAT -->

    <div class="chat-box">

        <h2 class="chat-heading">
            Ask your AI Assistant
        </h2>

        <p class="chat-subtitle">
            Ask questions about clients, sales, invoices and products.
        </p>


        <!-- QUESTION FORM -->

        <form
            action="{{ route('ai.ask') }}"
            method="POST"
        >

            @csrf

            <div class="question-box">

                <input
                    type="text"
                    id="question"
                    name="question"
                    placeholder="Ask something like: How many clients do we have?"
                    required
                >

                <button
                    type="submit"
                    class="ask-btn"
                >
                    Ask AI ✨
                </button>

            </div>

        </form>



        <!-- AI ANSWER -->

        @if(session('answer'))

            <div class="answer">

                <div class="answer-header">

                    🤖

                    <span>
                        AI Response
                    </span>

                </div>

                <p>
                    {{ session('answer') }}
                </p>

            </div>

        @endif



        <!-- QUICK QUESTIONS -->

        <div class="examples">

            <h3>
                Quick Questions
            </h3>


            <div class="quick-buttons">


                <button
                    type="button"
                    class="quick-btn"
                    onclick="setQuestion('How many clients do we have?')"
                >
                    👥 Total Clients
                </button>


                <button
                    type="button"
                    class="quick-btn"
                    onclick="setQuestion('What are our total sales?')"
                >
                    💰 Total Sales
                </button>


                <button
                    type="button"
                    class="quick-btn"
                    onclick="setQuestion('What is our total revenue?')"
                >
                    📈 Total Revenue
                </button>


                <button
                    type="button"
                    class="quick-btn"
                    onclick="setQuestion('How many pending invoices?')"
                >
                    📄 Pending Invoices
                </button>


                <button
                    type="button"
                    class="quick-btn"
                    onclick="setQuestion('How many products do we have?')"
                >
                    📦 Total Products
                </button>


                <button
                    type="button"
                    class="quick-btn"
                    onclick="setQuestion('Give me a CRM summary')"
                >
                    📊 CRM Summary
                </button>


            </div>

        </div>



        <!-- CRM STATS -->

        <h3 class="stats-title">
            CRM Data Categories
        </h3>


        <div class="stats">


            <div class="stat">

                <strong>
                    👥
                </strong>

                <span>
                    Clients
                </span>

            </div>


            <div class="stat">

                <strong>
                    💰
                </strong>

                <span>
                    Sales
                </span>

            </div>


            <div class="stat">

                <strong>
                    📄
                </strong>

                <span>
                    Invoices
                </span>

            </div>


            <div class="stat">

                <strong>
                    📦
                </strong>

                <span>
                    Products
                </span>

            </div>


        </div>

    </div>

</div>



<script>

    function setQuestion(question) {

        document.getElementById('question').value = question;

        document.getElementById('question').focus();

    }

</script>


</body>

</html>