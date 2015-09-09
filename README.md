# stockwebsite
This is an ongoing solo-project I have been working on that allows users search for and view stocks in companies listed on the Australian Stock Exchange (ASX). My goal for this project is to gain some experience working with very large data sets as well as some experience working with automation within Laravel. 

At the moment, there are around 3000 stocks on the ASX. For every stock, I have collected historical trading data dating back as far as early 2000. The database table storing the historical financials has nearly 5 million rows, and grows by 3,000 each trading day. 

Throughout each trading day, at intervals of one minute, the website uses the Yahoo Finance API to download and store each stocks' metrics. At the end of each trading day, end of day tasks are executed that calculate interval gains as well as overall sector performance. Shortly after midnight each night, another task is executed which downloads the latest list of ASX listed companies from the ASX servers. 

I'm always looking for ways to improve my code, so if you've looked at the source code and have any suggestions, feel free to drop me a line. 
