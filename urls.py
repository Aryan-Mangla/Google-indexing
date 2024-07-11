import sys
from oauth2client.service_account import ServiceAccountCredentials
import httplib2
import json
import os

# Define a mapping of accounts to JSON key files
ACCOUNT_JSON_KEY_MAP = {
    "indexing-service-account@indexing-api-425509.iam.gserviceaccount.com": "indexing-api-425509-8d7b1ba92e5c.json",
    "gi-api-service-account@airlineofficehubs.iam.gserviceaccount.com": "airlineofficehubs-39fea80b9df9.json",
    "airlines-terminals-json@airlines-terminal.iam.gserviceaccount.com": "airlines-terminal-7da5ac526d4d.json",
    "gi-api-service-account@airlineofficehubs2.iam.gserviceaccount.com": "airlineofficehubs2-ff338111a5a0.json",
    "indexing-project-1@adept-student-425604-k6.iam.gserviceaccount.com": "adept-student-425604-k6-1cf7c4909b38.json",
    "gi-api-service-account@airlineofficehubs3.iam.gserviceaccount.com": "airlineofficehubs3-ff33e9b497ee.json"
    # Add more mappings as needed
}

SCOPES = ["https://www.googleapis.com/auth/indexing"]

def indexURL(url, json_key_file):
    credentials = ServiceAccountCredentials.from_json_keyfile_name(json_key_file, scopes=SCOPES)
    http = credentials.authorize(httplib2.Http())

    ENDPOINT = "https://indexing.googleapis.com/v3/urlNotifications:publish"
    
    content = {
        'url': url.strip(),
        'type': "URL_UPDATED"
    }
    json_ctn = json.dumps(content)
    
    response, content = http.request(ENDPOINT, method="POST", body=json_ctn)
    result = json.loads(content.decode())

    # For debug purpose only
    if "error" in result:
        print(f"Error({result['error']['code']} - {result['error']['status']}): {result['error']['message']}")
    else:
        print(f"urlNotificationMetadata.url: {result['urlNotificationMetadata']['url']}")
        print(f"urlNotificationMetadata.latestUpdate.url: {result['urlNotificationMetadata']['latestUpdate']['url']}")
        print(f"urlNotificationMetadata.latestUpdate.type: {result['urlNotificationMetadata']['latestUpdate']['type']}")
        if 'notifyTime' in result['urlNotificationMetadata']['latestUpdate']:
            print(f"urlNotificationMetadata.latestUpdate.notifyTime: {result['urlNotificationMetadata']['latestUpdate']['notifyTime']}")
        else:
            print("notifyTime key not found in the response.")

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python3 urls.py <URL> <Account>")
        sys.exit(1)
    
    url = sys.argv[1]
    account = sys.argv[2]

    # Debug: Print provided account and available accounts
    # print(f"Provided account: {account}")
    # print(f"Available accounts: {', '.join(ACCOUNT_JSON_KEY_MAP.keys())}")
    
    if account in ACCOUNT_JSON_KEY_MAP:
        json_key_file = ACCOUNT_JSON_KEY_MAP[account]
        print(f"Using JSON key file: {json_key_file}")  # Debug: Show the JSON key file being used
        
        if not os.path.exists(json_key_file):
            print(f"JSON key file not found: {json_key_file}")
            sys.exit(1)
        
        indexURL(url, json_key_file)
    else:
        print(f"Account {account} does not have a corresponding JSON key file.")
        sys.exit(1)
