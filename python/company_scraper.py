import sys
import subprocess
from urllib.parse import urlparse
from bs4 import BeautifulSoup


def scrape_company(url, forced_ip=None):

    print("Original URL:", url)

    parsed = urlparse(url)
    hostname = parsed.hostname

    if not hostname:
        raise Exception("Invalid website URL")

    print("Hostname:", hostname)

    # Use supplied IP if available.
    # Otherwise resolve hostname normally.
    if forced_ip:
        ip = forced_ip
        print("Using IP:", ip)
    else:
        import socket

        try:
            ip = socket.gethostbyname(hostname)
        except socket.gaierror:
            raise Exception(
                "Could not resolve hostname: " + hostname
            )

        print("Resolved IP:", ip)

    print("Starting curl request...")
    print()

    curl = r"C:\Windows\System32\curl.exe"

    # curl command
    command = [
        curl,

        # Follow redirects
        "--location",

        # Show errors
        "--show-error",

        # Silent progress
        "--silent",

        # Connection timeout
        "--connect-timeout",
        "15",

        # Maximum request time
        "--max-time",
        "30",

        # Browser-like User Agent
        "-A",
        "Mozilla/5.0",

        # Force hostname to the supplied IP
        "--resolve",
        f"{hostname}:443:{ip}",

        # Website URL
        url,
    ]

    try:

        result = subprocess.run(
            command,
            capture_output=True,
            text=True,
            encoding="utf-8",
            errors="replace"
        )

    except Exception as e:

        print("SCRAPING ERROR:")
        print(str(e))

        sys.exit(1)

    # curl failed
    if result.returncode != 0:

        print("SCRAPING ERROR:")

        error = result.stderr.strip()

        if error:
            print(error)
        else:
            print(
                "curl failed with exit code:",
                result.returncode
            )

        sys.exit(1)

    html = result.stdout

    if not html.strip():

        print("SCRAPING ERROR:")
        print("Website returned an empty response.")

        sys.exit(1)

    # Parse HTML
    soup = BeautifulSoup(
        html,
        "html.parser"
    )

    # -----------------------------
    # TITLE
    # -----------------------------

    title = "Not found"

    if soup.title:

        title = soup.title.get_text(
            strip=True
        )

    # -----------------------------
    # DESCRIPTION
    # -----------------------------

    description = "Not found"

    meta = soup.find(
        "meta",
        attrs={
            "name": "description"
        }
    )

    if meta:

        description = meta.get(
            "content",
            "Not found"
        )

    # -----------------------------
    # HEADINGS
    # -----------------------------

    headings = []

    for tag in soup.find_all(
        ["h1", "h2", "h3"]
    ):

        text = tag.get_text(
            " ",
            strip=True
        )

        if text:
            headings.append(text)

    # Remove duplicate headings
    headings = list(dict.fromkeys(headings))

    # -----------------------------
    # COMPANY INFORMATION
    # -----------------------------

    print("Company Information")
    print("--------------------")

    print(
        "Title:",
        title
    )

    print(
        "Description:",
        description
    )

    print()
    print("Headings:")

    if headings:

        for heading in headings:

            print(
                "-",
                heading
            )

    else:

        print(
            "- No headings found"
        )


# =================================
# MAIN
# =================================

if __name__ == "__main__":

    if len(sys.argv) < 2:

        print("SCRAPING ERROR:")
        print("Website URL is required.")

        sys.exit(1)

    website = sys.argv[1]

    forced_ip = None

    if len(sys.argv) >= 3:

        forced_ip = sys.argv[2]

    try:

        scrape_company(
            website,
            forced_ip
        )

    except Exception as e:

        print("SCRAPING ERROR:")
        print(str(e))

        sys.exit(1)