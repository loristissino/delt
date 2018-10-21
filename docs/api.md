# Application Program Interface

Since October 2018, LearnDoubleEntry.org offers an API access to the data.

API stands for "Application Program Interface". This means that other applications can access the data of a firm and, for instance, read the names of the accounts or create a new journal entry.

## API KEY

To use the API, a user needs to [create his/her own API key](/api/subscribe), a 16-digit hexadecimal number. The first part of the key is shown on the webpage after creation, the second one is sent via email to the user requesting it.

The API is ReSTful and uses HTTP basic authentication. This means that all requests should carry the header

    Authorization: Basic {credentials}

where `{credentials}` is the base64-encoded string coming from the `username:password` pair (you can use what you want as username, and you must use the API key as password). Most HTTP client libraries support this authentication scheme and take care of the implementation.

To simplify testing from a standard web browser (at least for GET requests) it is also possibile to append `?apikey={apikey}` to the URL.

## Endpoint

The endpoint for the use of the API is __https://learndoubleentry.org/api__.

## Calls

There are calls to retrieve, update, delete and create resources on the website. It is possible to act only on firms that are owned by the user who is using the API key.

### Firms

The list of owned firms can be obtained with:

    GET /api/firms
    
The result is obtained as a JSON-encoded array of firms:

    [
        {
            "slug": "5e905",
            "name": "My first firm",
            "currency": "USD",
            "url": "https://learndoubleentry.org/api/firm/slug/5e905"
        },
        {
            "slug": "ad596",
            "name": "My second firm",
            "currency": "EUR",
            "url": "https://learndoubleentry.org/api/firm/slug/ad596"
        }
    ]

Details about a single firm can be optained with:

    GET /api/firm/slug/{slug}
    
The result is obtained as a JSON-encoded object:

    {
        "slug": "5e905",
        "name": "My first firm",
        "description": "A Business in the US",
        "currency": "USD",
        "create_date": "2017-01-30 15:17:34",
        "language": "en_US",
        "owners": "John Doe",
        ...
    }

It is possible to edit some of the details of a firm by sending a PUT request with the same type of object in the body of the request:

    PUT /api/firm/slug/{slug}

    {
        "slug": "5e905",
        "name": "My first firm",
        "description": "A Business in the UK",
        "currency": "GBP"
    }

### Accounts

The list of accounts of a firm can be obtained with:

    GET /api/accounts/slug/{slug}

The result is obtained as a JSON-encoded array of accounts.

### Sections

The list of sections of a firm can be obtained with:

    GET /api/sections/slug/{slug}

The result is obtained as a JSON-encoded array of sections.

### Journal entries

The list of journal entries of a firm can be obtained with:

    GET /api/journalentries/slug/{slug}

The result is obtained as a JSON-encoded array of journal entries.

Every journal entry has a numeric `id`.

It is possible to retrieve a specific journal entry with a `GET` request in this form:

    GET /api/journalentry/slug/{slug}/id/{id}

It is possible to delete a journal entry with a `DELETE` request:

    DELETE /api/journalentry/slug/{slug}/id/{id}
    
It is possible to edit the description or the date of a journal entry with a `PATCH` request, putting the JSON-encoded modified object in the body of the request:

    PATCH /api/journalentry/slug/{slug}/id/{id}

    {
        "date": "2018-10-08",
        "description": "Sales",
        "section_id": "1801"
    }
    
It is not possible to modify the single postings of the journal entry.

To add a new journal entry, make a `POST` request, putting the JSON-encoded object in the body of the request:

    POST /api/journalentries/slug/{slug}

    {
        "date": "2018-10-18",
        "description": "Sales",
        "section_id": "1801",
        "postings": [
            {
                "code": "00.03.04.03",
                "amount": 50,
            },
            {
                "code": "03.01.01.01",
                "amount": -50,
            }
        ]
    }
    

