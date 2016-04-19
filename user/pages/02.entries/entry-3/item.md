---
title: 'This... Is... Jeopardy! '
published: true
summary:
    enabled: '1'
    format: short
taxonomy:
    category: blog
    tag:
        - 'infoviz'
topojson: true
author:
    name: 'Joshua Appleman, Anand Rajagopal, Anubhav Gupta & Juan Shishido'
    org: 'University of California, Berkeley'
header_image: user/themes/isrtheme/images/jeopardy.png
---

####Data
The initial data set, found on Reddit [1], included qualitative data on over 200,000 Jeopardy! questions. It had data on the round that the question corresponded to, the questions and answers themselves, the dollar value of the question, and the show number and air date.

In our exploratory data analysis phase, we found several interesting results. For example, we found that the most frequent type of answers were related to geography. We also noticed, while looking at the average question value across years, that there was a large increase between 2001 and 2002. In 2001, the average question value was $496. It increased to $940 in 2002. With additional research, we found that the values doubled on November 26, 2001. This would inform some of the subsequent decisions we would make with respect to comparing episodes across time.

In processing the data, we also found that the number of episodes varied by season. This was not a function of the show, but a result of data not being available in the earlier years. The source of the data set posted on Reddit was from J! Archive, a "fan-created archive of Jeopardy! games and players."

Because Jeopardy! is not just about the questions and answers, we decided to obtain additional data to complement what we already had. From J! Archive, we scraped the individual episode scores. An example of the data is shown below.

 

This table shows the scores for each contestant on the first 10 questions in the Jeopardy! round for a particular episode. For each episode, we collected the scores data for every question in each of the three rounds. Not only did this provide the question-by-question scores as well as the total earnings, it also gave us a chance to explore the wagering dynamics of the Final Jeopardy! round. This quantitative data was used in the four of our visualizations: the heat map, the game line plot, the scatter plot, and the bipartite graph.

We also scraped the top 50 contestants and their earnings from the hall of fame section on jeopardy.com, "http://www.jeopardy.com/showguide/halloffame/50kplus/"

####Tools
In order to process the data, we used both Python and Excel. The exploratory work had two components. We used IPython notebooks, reading the data into pandas DataFrames, to both transform the data and extract features we would like to use in the visualizations. We used Tableau  to do deeper exploratory analysis of the data. We also used IPython notebooks for testing code for scraping the scores data. For this, we used both the pandas and BeautifulSoup modules. When we were done testing, we created Python scripts that were run on Harbinger.

For the visualization, we used: HTML/CSS, JavaScript, D3, HighCharts, JustInMind, Rhinoceros (CAD vector software good for tracing images to bring into Illustrator), Photoshop, and Illustrator.

####Process and Related Work
Assigning Gender to Contestants
In our data set we had 9101 unique contestants but we could not tell their gender from our web scraping. Of those, there were 1930 unique first names. To approximate their gender, we used their first names. First we downloaded a database from http://www.ssa.gov/oact/babynames/limits.html. That data set has the 1000 most popular new born baby names in the USA each year going back to 1880. It covers 74% of the US population. It has the name, sex and number of births. Some names obviously appear in both genders, such as Jordan for example, but in those cases we just assigned gender based on what was more probable.

By the time this process was finished, we still had about 500 names left that did not show up in the database. The next step was using websites and APIs like http://genderchecker.com, https://gender-api.com, and https://genderize.io. The free versions of these had limitations with how many names one could enter at a time and how many could be checked in a day. Running the names through this process got us down to 100 unknown names. At this point we manually typed the names in LinkedIn and Facebook and estimated if there were more female or male results based on the pictures. Some contestants have their picture on http://j-archive.com and we manually used that as well. With this new information, we were able to give all contestants a gender.

####Contestant and Gender Infographics and Line Chart
We did not want to immediately present the people visiting our visualization with something too dense and complex. We have deeper more multidimensional visualizations later on but we wanted to ease people into it. Narrative was important to us so we wanted to start lower on Cairo's (p.51) visualization wheel [1] then move upwards. 

  

The narrative begins by telling people a bit how people end up getting on the show. We found pictures of the Jeopardy podiums on Google Images, traced them in Rhinoceros (a CAD software) and imported the vectors into Illustrator to add color and text. The podiums were a way to start out the narrative in a playful way with simple numbers. The illustrations are familiar, light and decorative. The numbers provided are unidimensional. The fonts displayed are the actual fonts used on Jeopardy.

  

We then quickly transition into the ‘so what?' part of the narrative. In the illustration of the hands holding buzzers, we are pointing out that not only do fewer females win than males, they win less in proportion to the number of female contestants. Nail polish matching the buzzers was added to the thumbs to show femininity.

  

The first chart we have on the page shows the percent of female contestants and the percent of female winners from 1984-2014. Looking at Few's chapter on time-series analysis (p.146) we see that the height of the chart is important. Making it too short will make the variability difficult to detect. Making it too tall can exaggerate the variation and mislead the readers. Our goal with the height was to balance in between those extremes to show more females are coming onto the show and winning but the changes are not drastic. We are also using the Gestalt principle of similarity. The yellow color for female and blue for male matches the human isotypes in the next graphic. And the line type for contestant percentage and win percentage matches across genders. 

Another project from Few that was helpful in making this visualization was the stacked area chart on government spending (p. 305) [2]. In particular the suggestion to use tooltips to give extra information without cluttering the whole graphic was helpful.

  

 
 
Inspiration for the isotypes of people came from the project Cairo cited by Otto and Marie Meurath about Home and Factory Weaving in England (p.72). [3] The goal is to communicate a simple idea with clarity and power. Showing the stark contrast in the number of male and female writers should send a strong message about what could be causing the gender gap in Jeopardy! wins.

 


 

####Exploratory Data Analysis using Tableau
The jeopardy dataset from J Archive was pretty vast and had information covering different aspects of the game show.  Also given that, arguably, the main component of the data - the questions and answers; was text and therefore much more challenging to visualize. However we wanted to investigate if there were any underlying trends to this data. An overlying question or hypothesis that we were trying to answer was: Is there a smart way to study/prepare for Jeopardy?

Step 1: A logical point was to look at the distribution of the data. 
 

This helped clearly see that there was a huge skew in the data. This data has been mostly created by voluntary work of fans and this has been more diligently done after 1995.  To deal with this, we decided to analyze all our data by grouping them into 3 categories - 1984-1995, 1996-2002 and 2002-2012. The second dip was probably created as an  aftermath of the 9/11 attacks when there was either a dip in the airing of the shows or in its recording and archiving by fans. This would help us identify more meaningful trends in the data and rule out any bias created by the lack of data.

Step 2: Next step was to look at the categories over time to try and see if there any categories that are larger in proportion so that they can be considered more prominent. Traditionally fans have always suspected "Potpourri" to be a very popular category, but our analysis showed it different.

 	
This shows "Before and After" to be at the top followed by "Science", "Literature" and  "American History" before the crowd favourite "Potpourri". 

Step 3: To give this more context, we plotted how this varies by season as well. This provides some interesting insights. "Before and After"did not exist as a category until 1996. Yet, after that it has gone on to become the most popular category. In almost all the cases, there seems to be a peak in 1996-2002 even though this is not the largest category in terms of duration. This seems to have been a relatively less imaginative period in the history of the show where categories were repeated more often.

Another insight is how many topics seem to have an overlap. Many are vague, others overlap, and most seem to relate to Geography. Is there something to that?

 

Another view of the same data:

