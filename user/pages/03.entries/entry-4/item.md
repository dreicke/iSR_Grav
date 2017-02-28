---
title: 'Topic Modeling with Spark and LDA to Facilitate Legal Investigations'
header_image: 'user/themes/isrtheme/images/contract.jpg'
summary:
    enabled: '1'
    format: short
taxonomy:
    tag:
        - issue2
    category:
        - blog
topojson: true
author:
    name: 'Ryan Chamberlain, Arthur Mak, James Route, and Sayantan Satpati'
    org: 'University of California, Berkeley'

---

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
<!-- <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script> -->
<script src="//cdn.jsdelivr.net/jquery.color-animation/1/mainfile"></script>
<script src="http://a11y.nicolas-hoffmann.net/modal/js/jquery-accessible-modal-window-aria.js"></script>

<div id="endorsement" class="hidden modal">
<p style="text-align:center; color:#3E0C46;"><em>from Dr. James G. Shanahan, Lecturer, UC Berkeley School of Information</em></p>
<p>I recommend this paper for inclusion in the winter issue of iSchool Review. The application of topic modeling to a large document set is highly relevant to legal discovery—a field where machine learning and data science techniques offer much potential for improving the speed of document review. This paper is thorough in explaining the problems faced in legal discovery and the applicability of topic modeling to the problem. It offers an interesting application of topic modeling because the dataset is orders of magnitude larger than the corpuses reported in many previous studies. The use of TF-IDF with topic modeling also offers a good comparison against the baseline technique.</p>
<p>The paper also works well as a tutorial, laying out the steps needed to reproduce the architecture and replicate results. The discussion on working with a large number of files in Hadoop and Spark is a solid addition and may prove useful to other students facing similar challenges with organizing their data for analysis. The paper offers a good jumping-off point for future work, and shows promise for further useful results if the authors wish to continue the line of analysis.</p>

</div>

<a class="js-modal" data-modal-prefix-class="simple-animated" data-modal-content-id="endorsement" data-modal-title="Endorsement" data-modal-close-text="Close" data-modal-close-title="Close this modal window">Endorsed by Dr. James G. Shanahan, University of California, Berkeley <i class="fa fa-external-link-square" aria-hidden="true"></i></a>

###Introduction

The process of legal discovery, which includes the examination of materials prior to a trial to find useful evidence, has become increasingly challenged because of the rapid growth in electronically stored data. The traditional, manual review process scales poorly when faced with millions, billions, or even more documents. The challenges of manual review were well publicized almost 15 years ago during the federal investigation of Enron, which at the time was considered the largest computer forensics investigation ever conducted (Iwata, 2002). To process all of the information, the U.S. Department of Justice (DOJ) staffed a special task force that pored over the data for four years; the DOJ also contracted with three data recovery firms for technical assistance (Linder, 2014).

Predictably, the challenges of discovery have only worsened in the intervening time. In 2010, the Examiner for the U.S. Bankruptcy Court in the Southern District of New York faced the task of reviewing approximately 3 petabytes of data—or the equivalent of 350 billion pages—as part of the legal proceedings against Lehman Brothers . The Examiner used Boolean searches to reduce the page count to 40 million, and then employed more than 70 attorneys to conduct a manual review, which was still a daunting task for even a large group of reviewers (Baron, 2011).

As the capacity for digital storage grows, the traditional review techniques will become even less feasible, with an increased risk of missing key evidence. Boolean searches offer limited assistance when faced with an extremely large and mostly unknown dataset, as the examiner may have little insight into which keywords will return results relevant to the legal proceedings. Even after repeated successful searches there will likely be useful data that remains untouched and potentially undiscovered.

###One Popular Solution: Predictive Coding

Predictive coding is one solution to the discovery problem that has risen to prominence since the early 2000s. This approach iterates between manual review and an automated, machine learning process to scan rapidly through document collections. Predictive coding first requires human experts to label a sample of documents as relevant or irrelevant. Then, the algorithm builds a model, such as a classification tree, from the labeled data and applies it to the rest of the dataset, predicting whether the remaining documents are relevant. After running the model, humans review a sample of the results to correct inaccurate labels, rebuild the model, and then rerun it. Following this iterative process, the algorithm provides the reviewers with a pared down set of potentially relevant documents, which are then reviewed manually (Baron, 2011; Barry, 2012; Remus, 2014).

Multiple experiments have produced results indicating that predictive coding yields accuracies equal to or better than manual review, and in an experiment with 60,000 documents, predictive coding was six times faster than Boolean searches coupled with manual review (Bordon, 2010). However, for very large datasets, predictive coding can still be very time intensive, and it is limited in its ability to provide useful information about the documents beyond classification.

###Our Solution: Topic Modeling Using LDA

We elected to implement an alternative approach using topic modeling. Topic modeling can help examiners understand their dataset by grouping documents into a preset number of topics. Each topic is defined by a set of words that occur within the document text. For topics that are particularly relevant, the algorithm can identify documents that are most strongly associated with the topic. The topic’s related words can also seed Boolean searches to identify additional documents, which can be set aside for the manual review process.

We use the Latent Dirichlet Allocation (LDA) algorithm to perform topic modeling. We chose this algorithm based on its strong performance in previous studies related to discovery and document retrieval, as well as its integration into the Apache Spark computing engine (de Waal, Venter, & Barnard, 2008; George, Puri, Wang, Wilson, & Hamilton, 2014). LDA models a collection of documents as a set of probability distributions that explain a generative process of the collection (see Figure 1).  It assumes that the collection is made up of a set of documents (M) with words (N) and a set of topics (𝛳).  Each topic is a distribution over a set of words, and each document is a distribution of topics.  These distributions are defined by latent variables (α and 𝛽) whose values are inferred by analyzing the entire document collection (Blei, Ng, & Jordan, 2003).
 
**Figure 1**

![schematic diagram](topicmodel1.png)

We evaluated the topic modeling-based approach using the Enron email dataset (hosted on [Amazon](https://aws.amazon.com/datasets/enron-email-data/)), which we chose because of its public availability and its size—more than 1.2 million emails. The dataset was originally used in the Federal Energy Regulatory Commission’s (FERC) investigation of Enron, making it directly applicable to the task of discovery (Information Released, 2013; Enron Email Data, 2012).

###Tools and Libraries Used
We implemented a cloud-based architecture for storage, preprocessing, and performing LDA, as the dataset is impractically large to run on a single machine. The architecture features Apache Hadoop and Spark, making it scalable to fit larger datasets. We chose the SoftLayer cloud so that we could implement our own object storage from scratch and more closely emulate a hybrid or private cloud architecture that a legal investigation might employ. A complete list of our tools is below:

_Table 1: Summary of Software Tools Used_

| Tool                               | Rationale                                                                                                                                                                         |
|------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| SoftLayer,Cloud and Python Library | Primary cloud infrastructure enabling use of variable resources at different stages of the project. We used Python scripts to automate the setup and takedown of cloud resources. |
| Swift Object Storage               | Efficient permanent storage location for the raw and intermediate data. SoftLayer’s Swift-based object storage was cost-efficient, reliable, and had programmatic API access.     |
| Python Fabric                      | Provided an automated procedure for setting up the architecture and deploying the application.                                                                                    |
| Apache Hadoop                      | Distributed storage across our cluster of virtual machines. Also provided an efficient platform to preprocess data using MapReduce routines.                                      |
| Natural Language Toolkit (NLTK)    | Performed preprocessing on the text data, such as stemming.                                                                                                                       |
| Apache Spark/Scala/MLLib           | Performed the LDA processing.                                                                                                                                                     |

###Setup and Execution

Prior to running LDA, we ran several stages of preprocessing to clean the data and optimize it for use in Hadoop and Spark. Although we do not provide details on how to set up every component, our [GitHub repository](https://github.com/rmchamberlain/w251-project) offers instructions on how to replicate our build process. We also repeatedly tested our scripts to confirm that our infrastructure could be reliably rebuilt from scratch.

_1. Load Enron data from Amazon’s public distribution into Swift_

From Amazon’s public Enron dataset, we used the original data published by FERC, as its format required less preprocessing. Due to the size of the entire Amazon dataset (210 GiB), we set up a small virtual machine (VM) in the cloud to acquire the data and transfer it to our Swift object store.

_2. Create a temporary server and perform the initial cleaning stage_

The Enron data are organized into 151 folders (in ZIP format), each containing the emails sent by a particular Enron executive. Each email is an individual file, as are any attachments. We opted to exclude attachments from our analysis, as many were non-textual and unsuitable for use with LDA.
We deployed a single VM in Softlayer with sufficient memory (12 GB) to avoid expensive writes to disk. The VM retrieved each folder, extracted the contents, and cleaned individual emails by removing the footer and extraneous formatting characters. Each cleaned text file was saved back into Swift.

_3. Create a Spark cluster, perform final data cleaning, and load dataset into Hadoop Distributed File System (HDFS)_

We first set up a cluster of five SoftLayer VMs, each with four vCPUs, 16GB of memory, and 100GB of storage. We used a Python script and the Fabric library to automate this process and install and configure Spark, Hadoop, NLTK, and other dependent libraries on the cluster.

We then transferred the text files from Swift to HDFS, merging the files so that emails from the same original directory were concatenated into one file. This was a critical step, as HDFS is designed to work with large files, rather than many smaller ones. (Placing 1.2 million small files on HDFS and running operations on the data can cause the NameNode to run out of memory.) Furthermore, Hadoop spawns Map tasks based on the number of input files, which creates a great deal of overhead that slows execution when there are many inputs. In our preliminary tests, processing the un-merged files was dramatically slower and tended to cause the DataNodes to fail after running out of memory.

We performed final preprocessing as a MapReduce job (map only), using NLTK for tokenization, stopword removal, and stemming. This process prevented the LDA algorithm from taking common, insignificant words into account, increasing the chances of generating useful topics. Similarly, stemming reduced words to their root forms (e.g., ‘follow’ and ‘following’ are treated as the same word). These processes also decreased the vocabulary size, which improved the runtime of LDA. The clean data were written back to HDFS, with each file containing one email per line.

_4. Run LDA using Spark MLLib_

We chose Spark as our compute engine because MLLib contains an implementation of LDA, and because Spark’s in-memory processing and parallelization options offered significant speed advantages over Hadoop.
Spark’s MLLib made running LDA straightforward. The 151 input files were read from HDFS into memory, and a flatMap operation separated the emails within Spark’s RDD data structure. Spark calculated word counts across all of the documents, removed the most common words, and then ran LDA on the filtered document set. The results of LDA—the topics and their 20 most strongly associated words—were written back to HDFS. We ran LDA with two separate document-weighting schemes and specified a varying number of topics (as described later) to observe the effects on the resultant topic models.

###Results

The topic-modeling pipeline run successfully under Spark processed all emails and provided a list of 20 topics in approximately two hours. (Preprocessing was slightly longer.) Each topic comprised a list of 20 words, along with the log-likelihood score of the word occurring in a document with the given topic. We judged seven of the topics as relevant to the investigation of Enron, whereas the remaining 13 varied from slightly useful to irrelevant. The LDA algorithm did not name the topics (they were simply lists of words) so we gave each one a name based on its contents, as is customary when discussing topic modeler output. Table 2 summarizes the 20 topics produced by the model, and the subsequent tables provide the word lists for five relevant and five irrelevant topics.


_Table 2: Summary of Generated Topics (High-Relevance Topics in **Bold**)_

<table>
	<tr>
		<td><b>Structure</b></td>
		<td>Discussion</td>
		<td>Legal Terms</td>
		<td>Names</td>
		<td><b>Investing</b></td>
	</tr>
	<tr>
		<td>Names 2</td>
		<td>Communications</td>
		<td>Recreation</td>
		<td>Conversation</td>
		<td><b>Regulations</b></td>
	</tr>
	<tr>
		<td>Names 3</td>
		<td><b>R&amp;D</b></td>
		<td>IT</td>
		<td><b>Energy Transport</b></td>
		<td>Legal Terms 2</td>
	</tr>
	<tr>
		<td>Names 4</td>
		<td><b>Business Terms</b></td>
		<td>IT 2</td>
		<td><b>Business Partners</b></td>
		<td>Names 5</td>
	</tr>
</table>


_Table 3: Stemmed Words from Five Topics of High Relevance_

| Regulations | Investing | R&D      | Energy Transport | Business Partners |
|-------------|-----------|----------|------------------|-------------------|
| generat     | million   | futur    | capac            | citi              |
| plant       | stock     | research | pipelin          | permian           |
| particip    | news      | trader   | volum            | tenn              |
| polici      | share     | link     | area             | east              |
| public      | invest    | weather  | transport        | elpo              |
| govern      | online    | model    | deliveri         | nymex             |
| ferc        | fund      | valu     | hour             | summer            |
| industri    | secur     | natur    | germani          | tetco             |
| suppli      | technolog | data     | total            | west              |
| regulatori  | quarter   | analysi  | outag            | socal             |
| board       | world     | carrfut  | firm             | gate              |
| competit    | network   | test     | storag           | sonat             |
| member      | earn      | differ   | data             | appalac           |
| action      | billion   | spread   | nomin            | questar4          |
| nyiso       | analyst   | high     | flow             | oklahoma          |
| general     | save      | average  | natur            | winter            |
| feder       | investor  | paper    | meter            | appalach          |
| regul       | money     | forecast | paso             | shpchan           |
| committee   | profit    | chart    | pool             | hehub             |
| nation      | york      | contain  | peak             | demarcat          |


_Table 4: Stemmed Words from Five Topics of Low Relevance_

| Names 2      | Personal | Recreation | Conversation | Discussion |
|--------------|----------|------------|--------------|------------|
| kate         | check    | game       | peopl        | talk       |
| larri        | hotmail  | play       | thing        | sure       |
| syme         | bass     | sunday     | realli       | morn       |
| lesli        | home     | season     | littl        | user       |
| matt         | yahoo    | ticket     | even         | soon       |
| elizabeth    | night    | event      | friend       | tomorrow   |
| doug         | weekend  | pass       | never        | possibl    |
| sager        | travel   | sign       | much         | move       |
| stephani     | great    | player     | tell         | thought    |
| campbel      | matthew  | fantasi    | great        | present    |
| baughman     | room     | miss       | life         | done       |
| stacey       | lenhart  | place      | didn         | suggest    |
| jason        | vacat    | expect     | school       | peopl      |
| karen        | visit    | agent      | went         | understand |
| clark        | love     | center     | better       | feel       |
| russel       | card     | yard       | recruit      | feedback   |
| murphi       | hotel    | holiday    | true         | appreci    |
| gilbert      | dinner   | activ      | love         | problem    |
| counterparti | happi    | saturday   | everi        | status     |
| roger        | holiday  | practic    | around       | password   |

All of the topics, even those of low relevance, display sound coherence, indicating that the model has clustered the documents effectively. The high-relevance topics are filled with terms that are likely to be of use to an investigator, such as the names of regulatory agencies, business partners, and terms related to investments. Notably, five of the low-relevance topics consist almost solely of first or last names. At a glance, these do not appear to be very useful. However, these groupings may indicate people who communicate with each other frequently, and thus could be useful as a starting point for a network analysis.

We also note that only a minority of the generated topics would be relevant to an investigation. This is not a failure of the model, but rather is to be expected. In document review and discovery processes, it is often the case that a small number of documents will be relevant. Similarly, in the Enron corpus, one might expect a small fraction of the emails to contain useful data for an investigation; many emails might simply lack necessary detail, refer to aspects of work unrelated to criminal business practices, or discuss non-work matters. The topics generated by LDA reflect what is likely a lower proportion of relevant emails to review.

###Improving the Model

We attempted to improve the output of the LDA algorithm by adjusting the number of topics and changing the term weighting for the vocabulary. We started by focusing on the number of topics. We initially chose 20, viewing this number as generating a manageable amount of data to review. We tried increasing this number to a 50 topics to see if the models generated additional useful topics or increased intra-topic cohesion. The results were in fact discouraging, as the additional topics tended to yield more lists of names or common terms used in conversation and business emails, rather than more relevant terms. In addition, LDA runtime increased roughly linearly with additional topics, up approximately five hours for 50 topics.
We then tried implementing term frequency-inverse document frequency (TF-IDF) weighting to test its effect on the topic outputs. LDA normally uses a bag-of-words model and whole-number term-frequency counts. However, the MLLib implementation of LDA accepts fractional term counts in its input, which allows us to use TF-IDF. The potential benefit of TF-IDF is that it might help LDA identify important words for the topic model and ignore less useful words. Our existing LDA code already calculated term frequencies, so we created an additional RDD in Spark to calculate IDF and used it to divide the term frequencies. The results from using TF-IDF were initially encouraging, but the algorithm only generated five useful topics, two fewer than the baseline method. The model offered an improvement by creating fewer topics that consisted solely of employee names, but it also created useless topics containing pleasantries and words commonly found in emails (e.g., please, thank, sincerely, etc.).

After running LDA under different parameters, we concluded that our original model with 20 topics offered the best combination of useful output and efficient calculation time. Additional topics and TF-IDF weighting offered little to no improvement and increased processing time.

###Implications and Conclusion

Topic modeling on the Enron dataset ran in approximately two hours using a relatively small cloud-based cluster. This is a small fraction of the time and cost required to manually review documents or use a machine-assisted technique such as predictive coding. Topic modeling is not intended to replace a thorough review process; instead it offers an efficient method for exploring a document corpus, and can be synergistically combined with other technologies and review methods.
One use of the topic model is to generate terms to search the document set. In the discovery process, document searches may be used to reduce the set to a more manageable size, after which manual review or predictive coding can be conducted. An examiner must first determine the search terms, which can be challenging when facing a large, unknown document set. The topic model provides a starting point, as all of the words it returns are found in the document set. The topics themselves can also lead to additional search terms; a topic containing words related to energy regulation, for example, may suggest that other terms related to the topic will be fruitful. Even if an examiner has already conducted exhaustive searches, a topic model can help describe the contents of the document set and shed light on document clusters of interest that have yet to be discovered by searching.

The topic modeler and the underlying architecture we constructed can also be extended to create an iterative approach to reducing the document set while minimizing human intervention, saving both time and cost. In addition to generating topics, LDA can also return a set of documents most strongly associated with a given topic. This can be combined with a cloud-deployable search engine, such as ElasticSearch, to identify documents from relevant topics. The topic modeler can be run again on the subsets for each topic, generating smaller and more specific document sets for review. This approach will scale well to larger document sets, as more cloud resources can be marshaled proportionally to the corpus size.
In sum, topic modeling provided an efficient method to explore a document set that was too large to be checked by human reviewers in a reasonable amount of time. The LDA algorithm can work in concert with machine-assisted review, or can be used on its own to identify relevant documents. As the inevitable growth of digitally stored data makes human participation in the discovery process increasingly difficult, unsupervised methods running on an elastic architecture can help describe a corpus and identify relevant documents within relatively short timeframes, leaving more time and resources for experts to review a smaller subset of potentially relevant documents.
 
##Works Cited

* Bordon, B. (2010, October 1). The Demise of Linear Review. Retrieved from http://upc.utah.gov/materials/2015civil/The_Demise_of_Linear_Review.pdf

* Baron, J. R. (2011). Law in the Age of Exabytes: Some Further Thoughts on 'Information Inflation' and Current Issues in E-Discovery Search. Rich. JL & Tech., 17, 9-16.

* Barry, N. (2012). Man versus machine review: the showdown between hordes of discovery lawyers and a computer-utilizing predictive-coding technology. Vand. J. Ent. & Tech. L., 15, 343.

* Blei, D. M., Ng, A. Y., & Jordan, M. I. (2003). Latent dirichlet allocation. Journal of machine Learning research, 3(Jan), 993-1022.

* de Waal, A., Venter, J., & Barnard, E. (2008, January). Applying topic modeling to forensic data. In IFIP International Conference on Digital Forensics (pp. 115-126). Springer US.

* Enron Email Data. (2012, February 14). Retrieved from https://aws.amazon.com/datasets/enron-email-data/

* George, C. P., Puri, S., Wang, D. Z., Wilson, J. N., & Hamilton, W. F. (2014, May). SMART Electronic Legal Discovery Via Topic Modeling. In FLAIRS Conference.

* Information Released in Enron Investigation. (2013, April 1). Retrieved from http://www.ferc.gov/industries/electric/indus-act/wec/enron/info-release.asp

* Iwata, E. (2002, February 18). Enron case could be largest corporate investigation. USA Today. Retrieved from http://usatoday30.usatoday.com/tech/news/2002/02/19/detectives.htm

* Linder, D. O. (2014). The Enron (Ken Lay and Jeff Skilling) Trial: An Account. Retrieved from http://law2.umkc.edu/faculty/projects/ftrials/enron/enronaccount.html

* Remus, D. (2014). The Uncertain Promise of Predictive Coding. Iowa Law Review, 99, 101.

<script>

// from http://stackoverflow.com/questions/17534661/make-anchor-link-go-some-pixels-above-where-its-linked-to
$(document).ready(function () {
    $('a').on('click', function (e) {
        // e.preventDefault();

        var target = this.hash,
            $target = $(target);

       $('html, body').stop().animate({
        'scrollTop': $target.offset().top-100
    }, 900, 'swing', function () {
    });

       console.log(target);

       $(target).animate({backgroundColor: '#ddd1e7'});
       $(target).animate({backgroundColor: 'white'}, 4000);

       // $(target).animate({
       //    fontWeight:400
       //  }, 8000, function() {

       // });


        console.log(window.location);

        return false;
    });

});

$('#showanswer').click(function(){
    $('p.answer').css("display","inline-block");
});


</script>
