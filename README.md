# stockwebsite

Publically available at http://stocks.westhorburn.com/

This is an ongoing solo-project I have been working on that allows users to search for and view stocks listed on the Australian Stock Exchange (ASX). My goal for this project is to gain experience working with very large data sets as well as some experience working with automation within Laravel. 

At the moment, there are around 3000 stocks on the ASX. I used the Yahoo Finance API to locate historical trading data dating back as far as early 2000. The database table for the historical financials has over 5 million rows, and grows by 3,000 each trading day. 

At regular intervals throughout each trading day, stocks' metrics are downloaded, processed and saved. Sector performance and stock interval gains/losses are calculated at the end of each trading day. Shortly after midnight each night, another task is executed which downloads the latest list of ASX listed companies from the ASX servers. 

From the website, users can view the best/worst performing stocks and sectors. Clicking on individual stocks displays information about the company, a historical price graph as well as some related stocks. With the use of indexes in MySQL, querying a table containing almost 5 Million rows takes only a fraction of a second.

I'm always looking for ways to improve this project, so if have any suggestions, feel free to drop me a line. 
