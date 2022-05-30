# About Kuharica

With 'Kuharica' you can find easy recipes to make at home.

- Simple API with one endpoint
- Search for specific recipes based on many number of parameters

Any issues, send PM and I'll sort it out.

---
## Summary
* [Base URL](#base-url)
  * [Required GET parameters](#required-get-parameters)
  * [Optional GET parameters](#optional-get-parameters)
* [Response example](#response-example)
  * [Base URL response](#example-for-base-url-response)
  * [Custom URL response](#example-for-custom-url-response)


---
## Base URL

Base route looks like this

    Example: localhost/api/meals?lang=en

And can be complex as this

    Example: localhost/api/meals?lang=en&per_page=5&with=ingredients,category,tags&page=1&tags=2,175


### Required GET parameters:
- lang 
  - String of 2 characters only (char(2))
  - Selects language of data 
  - Options: 
    1. English (English) - 'en'
    2. Croatian (Hrvatski) - 'hr'
    3. German (Deutsch) - 'de'
    4. Italian (Italiana) - 'it'
    5. Spanish (Española) - 'es'
    

    Example: localhost/api/meals?lang=en

### Optional GET parameters:
- per_page
  - INT (greater than 0)
  - Anything else resets url back to base url
  - Default value is 5
  - Determines how many meals are shown per page
  

    Example: localhost/api/meals?lang=en&per_page=10
    
- page
  - INT (greater than 0) 
  - Anything else resets url back to base url
  - Default value is 1 (default per_page is 5)
  - Determines which page is shown to user
  

    Example: localhost/api/meals?lang=en&page=3
    
- category
  - INT (greater than zero) | NULL | !NULL (case-sensitive)
  - Anything else resets url back to base url
  - Selects meals that have requested category
    - NULL shows all meals that don't have category
    - !NULL shows all meals that have a category (where value is not null)
    - INT shows meals with selected category


    Example: localhost/api/meals?lang=en&category=!NULL
    
- tags
  - String of integers with comma separation (no space after comma and no trailing comma)
  - Anything else resets url back to base url
  - Selects meals that have all tags required (no partial, all of them)
  
  
    Example: localhost/api/meals?lang=en&tags=23,176
  
- with
  - String of with up to three times (category|tags|ingredients) with comma separation (no space after comma)
  - Anything else resets url back to base url
  - Selects extra data for selected
    - category - shows extra data such as title and slug
    - tags - shows extra data such as id, title and slug
    - ingredients - shows extra data such as id, title and slug 
    
    
    Example: localhost/api/meals?lang=en&with=category,tags,ingredients

- diff_time
  - UNIX timestamp (integer greater than 0)
  - Anything else resets url back to base url 
  - Returns all meals that have been modified (including deleted) after the UNIX timestamp
  - Shows current status of the meal (create|modified|deleted)


    Example: localhost/api/meals?lang=en&diff_time=1234567890


## Response example

### Example for base url response

    URL: localhost/api/meals?lang=en

```yaml
{
    "meta": {
        "current_page": 1,
        "totalItems": 590,
        "itemsPerPage": 10,
        "totalPages": 59
    },
    "data": [
        {
            "id": 3,
            "title": "Jezik: en - Naslov jela - 3 Omnis in porro sit.",
            "description": "Jezik: en - Opis jela -3 Ad a quibusdam exercitationem omnis dolores quam. Non quia sunt laborum sed accusamus.",
            "status": "created",
            "category": 17
        },
        {
            "id": 4,
            "title": "Jezik: en - Naslov jela - 4 Est ut omnis nulla et sit.",
            "description": "Jezik: en - Opis jela -4 Est eos quibusdam molestiae ducimus. Rerum inventore ratione et officiis nemo quos.",
            "status": "created",
            "category": 8
        },
        {
            "id": 5,
            "title": "Jezik: en - Naslov jela - 5 Doloremque ullam qui qui voluptas placeat.",
            "description": "Jezik: en - Opis jela -5 Porro qui ratione voluptatibus natus. Quo beatae enim ut praesentium. Ut aut ut maxime.",
            "status": "created",
            "category": 14
        },
        {
            "id": 6,
            "title": "Jezik: en - Naslov jela - 6 Animi nihil perspiciatis neque nisi.",
            "description": "Jezik: en - Opis jela -6 Omnis eos id illum perspiciatis culpa earum. Sapiente dolorem ut et.",
            "status": "created",
            "category": null
        },
        {
            "id": 7,
            "title": "Jezik: en - Naslov jela - 7 Sequi aut quos qui nisi et in voluptatem.",
            "description": "Jezik: en - Opis jela -7 Hic vel mollitia sequi qui aut. Et error ut illum. Quia qui inventore sit.",
            "status": "created",
            "category": 9
        },
        {
            "id": 8,
            "title": "Jezik: en - Naslov jela - 8 Iure enim ut qui ex quia aut quo.",
            "description": "Jezik: en - Opis jela -8 A distinctio numquam cum in. Dolorem aut in qui et eaque. Eos optio odit sit quidem.",
            "status": "created",
            "category": 15
        },
        {
            "id": 9,
            "title": "Jezik: en - Naslov jela - 9 Maxime sint tempora et rem et voluptas quidem.",
            "description": "Jezik: en - Opis jela -9 Culpa dolores alias modi illum quia praesentium. Est et accusamus nihil dolor inventore eligendi.",
            "status": "created",
            "category": 2
        },
        {
            "id": 10,
            "title": "Jezik: en - Naslov jela - 10 Veritatis ab error consectetur id ut.",
            "description": "Jezik: en - Opis jela -10 Deleniti et ut voluptatum et alias. Ipsa sed aut aut quod dolor.",
            "status": "created",
            "category": 17
        },
        {
            "id": 11,
            "title": "Jezik: en - Naslov jela - 11 Voluptatem ut illum beatae non.",
            "description": "Jezik: en - Opis jela -11 Dolores officiis iure iusto. Aut sunt molestiae corporis consequatur.",
            "status": "created",
            "category": 16
        },
        {
            "id": 12,
            "title": "Jezik: en - Naslov jela - 12 Libero sit consequatur dolore dolorum.",
            "description": "Jezik: en - Opis jela -12 Explicabo aut illo laudantium voluptatem. Ex autem vel ea. Voluptatem nihil quis ut consectetur.",
            "status": "created",
            "category": 9
        }
    ],
    "links": {
        "prev": null,
        "next": "http://127.0.0.1:8000/api/meals?page=2&lang=en",
        "self": "http://127.0.0.1:8000/api/meals?page=1&lang=en"
    }
}
```
### Example for custom url response

    URL: localhost/api/meals?per_page=5&tags=2&lang=hr&with=ingredients,category,tags&diff_time=1493902343&page=2

```yaml
{
    "meta": {
        "current_page": 2,
        "totalItems": 8,
        "itemsPerPage": 5,
        "totalPages": 2
        },
    "data": [
        {
            "id": 387,
            "title": "Jezik: hr - Naslov jela - 387 Et vero alias quis rerum.",
            "description": "Jezik: hr - Opis jela -387 Velit laborum aut ratione. Hic quo sed amet consequatur. Recusandae at molestias sit ut.",
            "status": "created",
            "category": {
                "id": 12,
                "title": "Jezik: hr - Naslov kategorije - 12 Amet ipsa amet impedit sunt. Minus velit sit sunt esse vel aspernatur.",
                "slug": "category-12"
            },
            "tags": [
                {
                    "id": 2,
                    "title": "Jezik: hr - Naslov sastojka - 2 Iste voluptas ut maiores beatae voluptatem. Voluptatem rerum enim expedita omnis eligendi corrupti.",
                    "slug": "tag-2"
                }
            ],
            "ingredients": [
                {
                    "id": 94,
                    "title": "Jezik: hr - Naslov sastojka - 94 Culpa eum tenetur quis magnam. Quibusdam qui quae quia exercitationem mollitia aut.",
                    "slug": "sastojak-94"
                },
                {
                    "id": 5,
                    "title": "Jezik: hr - Naslov sastojka - 5 Sint minus in sed quia quia alias ratione. Magni voluptas fugit quia velit deleniti magni aut.",
                    "slug": "sastojak-5"
                },
                {
                    "id": 86,
                    "title": "Jezik: hr - Naslov sastojka - 86 Qui sint sit cumque ipsa est. Adipisci qui omnis et consequatur. Corrupti soluta incidunt labore.",
                    "slug": "sastojak-86"
                },
                {
                    "id": 24,
                    "title": "Jezik: hr - Naslov sastojka - 24 Voluptatem eaque ut quia nam unde. Non porro assumenda eum odio id eius qui.",
                    "slug": "sastojak-24"
                },
                {
                    "id": 68,
                    "title": "Jezik: hr - Naslov sastojka - 68 Id cum reprehenderit sed aut. Ut commodi et a sapiente eum.",
                    "slug": "sastojak-68"
                }
            ]
        },
        {
            "id": 535,
            "title": "Jezik: hr - Naslov jela - 535 Eius ut rem doloribus saepe animi eum ipsam.",
            "description": "Jezik: hr - Opis jela -535 Culpa et odio quo omnis et earum repellat a. Quia enim rem nemo ducimus. Quis tempore quisquam est.",
            "status": "created",
            "category": {
                "id": 16,
                "title": "Jezik: hr - Naslov kategorije - 16 Fuga illum et autem eius. Vero exercitationem sapiente voluptatem aliquam.",
                "slug": "category-16"
                },
            "tags": [
                {
                    "id": 2,
                    "title": "Jezik: hr - Naslov sastojka - 2 Iste voluptas ut maiores beatae voluptatem. Voluptatem rerum enim expedita omnis eligendi corrupti.",
                    "slug": "tag-2"
                },
                {
                    "id": 96,
                    "title": "Jezik: hr - Naslov sastojka - 96 Repudiandae molestias pariatur dolor et. Quibusdam esse similique autem omnis velit.",
                    "slug": "tag-96"
                }
            ],
            "ingredients": [
                {
                    "id": 19,
                    "title": "Jezik: hr - Naslov sastojka - 19 Ipsa eligendi est voluptatem vitae. Atque earum sint delectus consequatur qui vero.",
                    "slug": "sastojak-19"
                }
            ]
        },
        {
        "id": 575,
        "title": "Jezik: hr - Naslov jela - 575 Possimus odit ex eveniet deserunt.",
        "description": "Jezik: hr - Opis jela -575 Ipsam earum qui aut amet nemo. Quam iure ducimus ratione rem. Natus quae quod temporibus quaerat.",
        "status": "created",
        "category": {
                "id": 17,
                "title": "Jezik: hr - Naslov kategorije - 17 Labore quo sit quos mollitia. Nihil ab dolor sed et ullam at.",
                "slug": "category-17"
            },
        "tags": [
            {
                "id": 187,
                "title": "Jezik: hr - Naslov sastojka - 187 Aliquam vitae ab voluptatibus nihil suscipit et aliquam. Exercitationem quidem ut in nesciunt.",
                "slug": "tag-187"
            },
            {
                "id": 169,
                "title": "Jezik: hr - Naslov sastojka - 169 Eum iure illum incidunt ducimus aut. Et vel repellat sit ea enim velit.",
                "slug": "tag-169"
            },
            {
                "id": 157,
                "title": "Jezik: hr - Naslov sastojka - 157 Et et vero neque soluta modi assumenda. Ad tempora ut sunt eligendi voluptas expedita reiciendis.",
                "slug": "tag-157"
            },
            {
                "id": 2,
                "title": "Jezik: hr - Naslov sastojka - 2 Iste voluptas ut maiores beatae voluptatem. Voluptatem rerum enim expedita omnis eligendi corrupti.",
                "slug": "tag-2"
            }
        ],
        "ingredients": [
            {
                "id": 51,
                "title": "Jezik: hr - Naslov sastojka - 51 Culpa dolorem libero facilis repellendus vitae mollitia rerum. Est dolores mollitia est.",
                "slug": "sastojak-51"
            }
            ]
        }
    ],
    "links": {
        "prev": "http://127.0.0.1:8000/api/meals?page=1&per_page=5&tags=2&lang=hr&with=ingredients,category,tags&diff_time=1493902343",
        "next": null,
        "self": "http://127.0.0.1:8000/api/meals?page=2&per_page=5&tags=2&lang=hr&with=ingredients,category,tags&diff_time=1493902343"
    }
}
```
