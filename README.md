# Investment Finch
## This project has been discontinued due to the closure of the Yahoo Finance API

The website is still live and all old data is still accessible. You can access it at http://investmentfinch.com.au/

## About

This is an ongoing solo-project I have been working on that allows users to search for and view stocks listed on the Australian Stock Exchange (ASX). My goal for this project is to gain experience working with very large data sets as well as some experience working with automation within Laravel. 

At the moment, there are a little over 3000 stocks on the ASX. I used the Yahoo Finance API to locate historical trading data dating back as far as early 2000. The database table for the historical financials has over 6 million rows, and grows by more than 3,000 each trading day. 

At regular intervals throughout each trading day, stocks' metrics are downloaded, processed and saved. Sector performance and stock interval gains/losses are calculated at the end of each trading day. Shortly after midnight each night, another task is executed which downloads the latest list of ASX listed companies from the ASX servers. Trend data for each stock is also calculated during this time.

From the website, users can view the best/worst performing stocks and sectors. Clicking on individual stocks displays information about the company, a historical price graph as well as some related stocks. With the use of indexes in MySQL, querying a table containing over 6 Million rows takes only a fraction of a second.

I'm always looking for ways to improve this project, so if have any suggestions, feel free to drop me a line. 

## Upward Trending Stocks

For many users, the upward trending stocks listed on the home page are the most interesting part of the site. Investment Finch uses 50 day and 200 day moving average data for each stock to identify upward trends and trend reversals. A stock is considered to be in an upward trend if its 50 day moving average has recently crossed, or is about to cross above its 200 day moving average. This pattern is often known as a 'Golden Cross,' and Investment Finch attempts to identify stocks at various stages of the golden cross.

### Disclaimer

investmentfinch.com.au accepts no responsibility for the accuracy or reliability of any information displayed on this website. No information provided on this website should be used for investment or trading decisions, nor should information on this website be considered financial advice. investmentfinch.com.au will not be responsible for any financial losses suffered.
