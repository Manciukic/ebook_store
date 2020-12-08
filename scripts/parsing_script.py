import requests
import os
import json
import pandas as pd
from random import randrange

try: 
    from BeautifulSoup import BeautifulSoup
except ImportError:
    from bs4 import BeautifulSoup



BASE_URL = "https://standardebooks.org/"
BASE_FOLDER = "ebooks/"

pages = []

books = []
genres = []
ebook_genres = []

MAX_PAGES = 37
for npage in range(1, MAX_PAGES+1):
    print("Scanning page {}".format(npage))
    req = requests.get(BASE_URL + "ebooks?page=" + str(npage))

    dir_html = req.text

    parsed_dir_html = BeautifulSoup(dir_html)

    for link in parsed_dir_html.select("main > ol li > a[href]"):
        pages.append(link['href'])
        

total = len(pages)
current = 1
current_genre = 1
for page in pages:
    try:
        page_html = requests.get(BASE_URL + page)
        page_html = BeautifulSoup(page_html.text)
        title = page_html.select_one(".ebook hgroup > h1").text
        author = page_html.select_one(".ebook hgroup > h2").text
        description = page_html.select_one("#description p").text
        dl_link = page_html.select_one('#download .epub')['href']
        tags = [tag.text for tag in page_html.select("#reading-ease .tags li a")]
        book = {}        
        book['id'] = current
        book['title'] = title
        book['author'] = author
        book['description'] = description
        book['price'] = 5 + randrange(100)/10

        for tag in tags:
            try:
                genre_id = [genre['id'] for genre in genres if genre['name'] == tag][0]
            except:
                genres.append({'id': current_genre, 'name': tag})
                genre_id = current_genre
                current_genre += 1

            ebook_genres.append({ 'ebook_id': current, 'genre_id': genre_id})

        book['path'] = dl_link.split('/')[-1]
        
        print("Downloading {}/{}: {}".format(current, total, title))


        r = requests.get(BASE_URL + dl_link)
        with open(BASE_FOLDER + book['path'], 'wb') as f:
            f.write(r.content)

        books.append(book)
        current += 1
    except:
        print("Error: skipping book")



books = pd.DataFrame(books)
books.to_csv("books.csv", index=False)

genres = pd.DataFrame(genres)
genres.to_csv("genres.csv", index=False)

ebook_genres = pd.DataFrame(ebook_genres)
ebook_genres.to_csv("ebook_genres.csv", index=False)