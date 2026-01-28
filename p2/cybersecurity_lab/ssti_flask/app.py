from flask import Flask, request, render_template_string

app = Flask(__name__)

@app.route('/', methods=['GET', 'POST'])
def index():
    name = "Guest"
    if request.method == 'POST':
        name = request.form.get('name', 'Guest')
    
    # VULNERABLE: Direct injection into template string
    template = f"""
    <!DOCTYPE html>
    <html>
    <head>
        <title>SSTI Lab</title>
        <style>
            body {{ font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }}
            .container {{ background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }}
            h1 {{ color: #333; }}
            input[type="text"] {{ width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }}
            button {{ background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; }}
            button:hover {{ background-color: #0056b3; }}
            .result {{ margin-top: 20px; padding: 10px; background-color: #e9ecef; border-radius: 4px; word-break: break-all; }}
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Hello, {name}!</h1>
            <p>Enter your name to personalize this page.</p>
            <form method="post">
                <input type="text" name="name" placeholder="Your Name">
                <button type="submit">Say Hello</button>
            </form>
            <div class="result">
                Try: {{{{ 7*7 }}}}
            </div>
        </div>
    </body>
    </html>
    """
    return render_template_string(template)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
