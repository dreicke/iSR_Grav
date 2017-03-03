---
title: 'A Comprehensive Guide to the Total Order Sort Design Pattern in MapReduce'
header_image: 'user/themes/isrtheme/images/mapper2.jpg'
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
    name: 'James G. Shanahan, Kyle Hamilton, Yiran Sheng'
    org: 'University of California, Berkeley'
---

<style>
.etabs { margin: 0; padding: 0; }
.tab { display: inline-block; zoom:1; *display:inline; background: #eee; border: solid 1px #999; border-bottom: none; -moz-border-radius: 4px 4px 0 0; -webkit-border-radius: 4px 4px 0 0; }
.tab a { font-size: 14px; line-height: 2em; display: block; padding: 0 10px; outline: none; }
.tab a:hover { text-decoration: underline; }
.tab.active { background: #fff; padding-top: 6px; position: relative; top: 1px; border-color: #666; }
.tab a.active { font-weight: bold; }
.tab-container .panel-container { background: #fff; border: solid #666 1px; padding: 10px; -moz-border-radius: 0 4px 4px 4px; -webkit-border-radius: 0 4px 4px 4px; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.easytabs/3.2.0/jquery.easytabs.min.js"></script>


<script type="text/javascript">
    $(document).ready( function() {
      $('.tab-container').easytabs();
    });
  </script>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
<!-- <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script> -->
<script src="//cdn.jsdelivr.net/jquery.color-animation/1/mainfile"></script>
<script src="http://a11y.nicolas-hoffmann.net/modal/js/jquery-accessible-modal-window-aria.js"></script>

<div id="endorsement" class="hidden modal">
<p style="text-align:center; color:#3E0C46;"><em>from Dr. James G. Shanahan, Lecturer, UC Berkeley School of Information</em></p>
<p>I am writing in support of the publication of the enclosed submission. Sorting lies at the core of data and data science. It is one of the most studied and documented areas of computer science. Sorting at scale though not a novelty has lacked a solid, clear, and thorough exposition. This submission addresses this need and provides informative details about the design, and development of total order sorting at scale in Map-Reduce frameworks such as Apache Spark, Hadoop and MRJob. As such it is very much a systems paper. The key contribution here is to provide a self-contained exposition of sorting at scale, a topic that few have described but is commonly required in practice. This submission contains an excellent introduction to total order sorting along with several self-contained examples in a Python notebook. For the practitioner, for the student, for the professor this is an amazing resource, introducing the basics while also demonstrating the key ideas through examples and working code. The authors provide an excellent example of data science in action, and this paper will be  highly instructive to students and teachers in information schools.</p>

</div>

<a class="js-modal" data-modal-prefix-class="simple-animated" data-modal-content-id="endorsement" data-modal-title="Endorsement" data-modal-close-text="Close" data-modal-close-title="Close this modal window">Endorsed by Dr. James G. Shanahan, University of California, Berkeley <i class="fa fa-external-link-square" aria-hidden="true"></i></a>




##Introduction

In this notebook we are going to demonstrate how to achieve Total Order Sort via three MapReduce frameworks: Hadoop Streaming, MRJob, and Spark. Hadoop Streaming and MRJob borrow heavily in terms of syntax and semantics from the Unix sort and cut commands, whereby they treat the output of the mapper as series of records, where each record can be interpreted as a collection of fields/tokens that are tab delimited by default. In this way, fields can be specified for the purposes of partitioning (routing), sometimes referred to as the primary key. The primary key is used for partitioning, and the combination of the primary and secondary keys (also specified by the programmer) is used for sorting.

We'll start by describing the Linux/Unix sort command (syntax and semantics) and build on that understanding to explain Total Order Sort in Hadoop Streaming and MRJob. Partitioning is not just matter of specifying the fields to be used for routing the records to the reducers. We also need to consider how best to partition the data that has skewed distributions. To that end, we'll demonstrate how to partition the data via sampling and assigning custom keys.

Lastly, we'll provide a Spark version of Total Order Sort.

At each step we are going to build on the previous steps, so it's important to view this notebook in order. For example, we'll cover key points for sorting with a single reducer in the Hadoop Streaming implementation, and these concepts will apply to the subsequent MRJob implementation.

##Anatomy of a MapReduce Job

When tackling large scale data problems with modern computing frameworks such as MapReduce (Hadoop), one generally uses a divide-and-conquer strategy to divide these large problems into chunks of key-value records that can be processed by individual compute nodes (via mapper/reducer procedures). Upon importing the data into the HDFS, it is chunked in the following way: typically, there is a chunk for each input file. If the input file is too big (bigger than the HDFS block size) then we have two or more map chunks/splits associated to the same input file. (Please refer to the method getSplits() of the FileInputFormat class in Hadoop for more details.)

Once the data has been uploaded to HDFS, MapReduce jobs can be run on the data. First we’ll focus on a basic MapReduce/Hadoop job. The programmer provides map and reduce functions and, subsequently, the Application Master will launch one MapTask for each map split/chunk.

<code>map(key1, value1) → list(key2, value2)</code>

<code>reduce(key2, list(value2)) → list(key3, value3)</code>

First, the map() function receives a key-value pair input, (key1, value1). Then it outputs any number of key-value pairs, (key2, value2). Next, the reduce() function receives as input another key-value pair, (key2, list(value2)), and outputs any number of (key3, value3) pairs.

Now consider the following key-value pair, (key2, list(value2)), as an input for a reducer:
list(value2) = (V1, V2, ..., Vn)

where there is no ordering between reducer values (V1, V2, ..., Vn).

The MapReduce framework performs the important tasks of routing, collating records with the same key, and transporting these records from the mapper to the reducer. These tasks are commonly referred to as the shuffle phase of a MapReduce job and are typically run in default mode (whereby the programmer does not customize the phase).

So, for completeness, a typical MapReduce job consists of three phases (that are similar in style for all MapReduce frameworks):

**def MapReduceJob(Mapper, Shuffler, Reducer):**
* Mapper (programmer writes)
    * Mapper Init (setups a state for the mapper if required)
    * map(key1, value1) → list(key2, value2)
    * Mapper Final phase (tears down the map and outputs the mapper state to the stream if required)
* Shuffle Phase(Partitioner=Key, Sort=(Key, alphanumeric increasing), Combiner=None)
    * Where the default behaviors for the shuffle are as follows:
        * Partitioner=Key,
        * Sort=(Key, alphanumeric increasing),
        * Combiner=None
    * Partitioner: specify key (or subset of fields) used for routing each record to the reducer
    * Sort specification: set of fields that make up the keys and the order required
    * Combiner: DEFAULT: no combiner
* Write the Reducer (programmer writes)
    * Reducer Init phase (setups a state for the reducer if required)
    * reduce(key2, list(value2)) → list(key3, value3)
    * Reducer Final phase (tears down the reduce task and outputs the reduce state to HDFS if required)

To perform a total order sort, one needs to override the default shuffle behavior by providing a custom partitioner, a custom sort specification (e.g., numeric/alphanumeric increasing decreasing, and a combiner Note that a combiner is not required for a total order sort but makes the MapReduce job more efficient).

![Total Order Sort, Step 1](images/anatomyMR.png)

__Figure 1: Anatomy of a Map-Reduce Job from input data to output data via map, shuffle, and reduce steps.__

##Terminology

__Apache Hadoop__ is a framework for running applications on large clusters built of commodity hardware. The Hadoop framework transparently provides applications both reliability and data motion. Hadoop implements a computational paradigm named Map/Reduce, where the application is divided into many small fragments of work, each of which may be executed or re-executed on any node in the cluster. In addition, it provides a distributed file system (HDFS) that stores data on the compute nodes, providing very high aggregate bandwidth across the cluster. Both MapReduce and the Hadoop Distributed File System are designed so that node failures are automatically handled by the Hadoop framework. ([http://wiki.apache.org/Hadoop/](http://wiki.apache.org/Hadoop/))

__MRJob__ is a Python library developed by Yelp to simplify writing Map/Reduce programs. It allows developers to test their code locally without installing Hadoop or run it on a cluster of choice. It also has extensive integration with Amazon Elastic Map Reduce. More information is available at [http://MRJob.readthedocs.io/en/latest/index.html](http://MRJob.readthedocs.io/en/latest/index.html). 

__Partial Sort__ - The reducer output will be lot of (partition) files, each of which contains key-value records that are sorted within each partition file based on the key. This is the default behavior for MapReduce frameworks such as Hadoop, Hadoop Streaming and MRJob. 

__Total Sort (Unordered Partitions)__ - Total sort refers to an ordering of all key-value pairs based upon a specified key. This total ordering will run across all output partition files unlike the partial sort described above. One caveat here is that partition files will need to be re-stacked to generate a total ordering (a small post-processing step that is required after the MapReduce job finishes).

__Total Sort (Ordered Partitions)__ - Total sort where the partition file names are also assigned in order.

__Secondary Sort__ - Secondary sorting refers to controlling the ordering of records based on the key and also using the values (or part of the value). That is, sorting can be done on two or more field values.

####Hadoop Streaming

Hadoop Streaming is a utility that comes with the Hadoop distribution. The utility allows a user to create and run MapReduce jobs with any executable or script as the mapper and/or the reducer.

~~~~
$HADOOP_HOME/bin/Hadoop  jar $HADOOP_HOME/Hadoop-streaming.jar \
    -input myInputDirs \
    -output myOutputDir \
    -mapper /bin/cat \
    -reducer /bin/wc
~~~~

In the above example, both the mapper and the reducer are executables that read the input from stdin (line by line) and emit the output to stdout. The utility will create a MapReduce job, submit the job to an appropriate cluster, and monitor the progress of the job until it completes. When an executable is specified for mappers, each mapper task will launch the executable as a separate process when the mapper is initialized.

As the mapper task runs, it converts its inputs into lines and feeds the lines to the stdin of the process. In the meantime, the mapper collects the line oriented outputs from the stdout of the process and converts each line into a key/value pair, which is collected as the output of the mapper. By default, the prefix of a line up to the first tab character is the key and the rest of the line (excluding the tab character) is the value. If there is no tab character in the line, then the entire line is considered the key and the value is null. However, this can be customized, as discussed later.

When an executable is specified for reducers, each reducer task launches the executable as a separate process, and then the reducer is initialized. As the reducer task runs, it converts its input key/values pairs into lines and feeds the lines to the stdin of the process. In the meantime, the reducer collects the line-oriented outputs from the stdout of the process, converts each line into a key/value pair, which is collected as the output of the reducer. By default, the prefix of a line up to the first tab character is the key and the rest of the line (excluding the tab character) is the value. However, this can be customized, as discussed later.

This is the basis for the communication protocol between the MapReduce framework and the streaming mapper/reducer.

##Examples of Different Sort Types (in context of Hadoop and HDFS)

Below is an example dataset in text format on HDFS. It includes three partitions in an HDFS directory. Each partition stores records in the format of {Integer} [TAB] {English Word}.

<pre>
files in hdfs directory
2016-07-20 22:04:56          0 _SUCCESS
2016-07-20 22:04:45    2392650 part-00000
2016-07-20 22:04:44    2368850 part-00001
2016-07-20 22:04:45    2304038 part-00002
</pre>



<div id="tab-container" class="tab-container">
  <ul class='etabs'>
    <li class='tab'><a href="#tabs1-html">Partial Sort</a></li>
    <li class='tab'><a href="#tabs1-js">Total Sort (Unordered Partitions)</a></li>
    <li class='tab'><a href="#tabs1-css">Total Sort (Ordered Partitions)</a></li>
  </ul>
  <div id="tabs1-html">
<table>
<tr>
<td><pre>file: part-00000</pre></td>
<td><pre>file: part-00001</pre></td>
<td><pre>file: part-00002</pre></td>
</tr>
<tr>
<td>
<pre>
<span style="color:red">27</span>   driver
<span style="color:red">27</span>   creating
<span style="color:red">27</span>   experiements
<span style="color:red">19</span>   consists
<span style="color:red">19</span>   evaluate
<span style="color:red">17</span>   drivers
<span style="color:red">10</span>   clustering
 <span style="color:red">9</span>   during
 <span style="color:red">9</span>   change
 <span style="color:red">7</span>   contour
</pre>
</td>
<td>
<pre>
<span style="color:red">30</span>   do
<span style="color:red">28</span>   dataset
<span style="color:red">15</span>   computing
<span style="color:red">15</span>   document
<span style="color:red">15</span>   computational
<span style="color:red">14</span>   center
 <span style="color:red">5</span>   distributed
 <span style="color:red">4</span>   develop
 <span style="color:red">3</span>   different
 <span style="color:red">2</span>   cluster
</pre>
</td>
<td>
<pre>
<span style="color:red">26</span>   descent
<span style="color:red">26</span>   def
<span style="color:red">25</span>   compute
<span style="color:red">24</span>   done
<span style="color:red">24</span>   code
<span style="color:red">23</span>   descent
<span style="color:red">22</span>   corresponding
<span style="color:red">13</span>   efficient
 <span style="color:red">1</span>   cell
 <span style="color:red">0</span>   current
</pre>
</td>
</tr>
<caption align='bottom'>Keys are assigned to buckets without any ordering. Keys are sorted within each bucket (the key is the the number in the first column rendered in red).</caption>
</table>
  </div>
  <div id="tabs1-js">
<table>
<tr>
<td><pre>file: part-00000</pre></td>
<td><pre>file: part-00001</pre></td>
<td><pre>file: part-00002</pre></td>
</tr>
<tr>
<td>
<pre>
<span style="color:red">19</span>   consists
<span style="color:red">19</span>   evaluate
<span style="color:red">17</span>   drivers
<span style="color:red">15</span>   computing
<span style="color:red">15</span>   document
<span style="color:red">15</span>   computational
<span style="color:red">14</span>   center
<span style="color:red">13</span>   efficient
</pre>
</td>
<td>
<pre>
<span style="color:red">10</span>  clustering
<span style="color:red">9</span>   during
<span style="color:red">9</span>   change
<span style="color:red">7</span>   contour
<span style="color:red">5</span>   distributed
<span style="color:red">4</span>   develop
<span style="color:red">3</span>   different
<span style="color:red">2</span>   cluster
<span style="color:red">1</span>   cell
<span style="color:red">0</span>   current
</pre>
</td>
<td>
<pre>
<span style="color:red">30</span>   do
<span style="color:red">28</span>   dataset
<span style="color:red">27</span>   driver
<span style="color:red">27</span>   creating
<span style="color:red">27</span>   experiements
<span style="color:red">26</span>   descent
<span style="color:red">26</span>   def
<span style="color:red">25</span>   compute
<span style="color:red">24</span>   done
<span style="color:red">24</span>   code
<span style="color:red">23</span>   descent
<span style="color:red">22</span>   corresponding
</pre>
</td>
</tr>
<caption align='bottom'>Keys are assigned to buckets according to their numeric value. The result is that all keys between 20-30 end up in one bucket, keys between 10-20 end up in another bucket, and keys 0-10 end up in another bucket. Keys are sorted within each bucket. Partitions are not assigned in sorted order.</caption>
</table>
  </div>
  <div id="tabs1-css">
<table>
<tr>
<td><pre>file: part-00000</pre></td>
<td><pre>file: part-00001</pre></td>
<td><pre>file: part-00002</pre></td>
</tr>
<tr>
<td>
<pre>
<span style="color:red">30</span>   do
<span style="color:red">28</span>   dataset
<span style="color:red">27</span>   creating
<span style="color:red">27</span>   driver
<span style="color:red">27</span>   experiements
<span style="color:red">26</span>   def
<span style="color:red">26</span>   descent
<span style="color:red">25</span>   compute
<span style="color:red">24</span>   code
<span style="color:red">24</span>   done
<span style="color:red">23</span>   descent
<span style="color:red">22</span>   corresponding
</pre>
</td>
<td>
<pre>
<span style="color:red">19</span>   evaluate
<span style="color:red">19</span>   consists
<span style="color:red">17</span>   drivers
<span style="color:red">15</span>   document
<span style="color:red">15</span>   computing
<span style="color:red">15</span>   computational
<span style="color:red">14</span>   center
<span style="color:red">13</span>   efficient
<span style="color:red">10</span>   clustering
</pre>
</td>
<td>
<pre>
<span style="color:red">9</span>    during
<span style="color:red">9</span>    change
<span style="color:red">7</span>    contour
<span style="color:red">5</span>    distributed
<span style="color:red">4</span>    develop
<span style="color:red">3</span>    different
<span style="color:red">2</span>    cluster
<span style="color:red">1</span>    cell
<span style="color:red">0</span>    current
</pre>
</td>
</tr>
<caption align='bottom'>Keys are assigned to buckets according to their numeric value. The result is that all keys between 20-30 end up in one bucket, keys between 10-20 end up in another bucket, and keys 0-10 end up in another bucket. Keys are sorted within each bucket. Here, partitions are assigned in sorted order, such that keys between 20-30 end up in the first bucket, keys between 10-20 end up in the second bucket, and keys 0-10 end up in the third bucket. We use the term buckets and partitions interchageably.</caption>
</table>
  </div>
</div>


Keys are assigned to buckets according to their numeric value. The result is that all keys between 20-30 end up in one bucket, keys between 10-20 end up in another bucket, and keys 0-10 end up yet in another bucket. Keys are sorted within each bucket. Here, partitions are assigned in sorted order, such that keys between 20-30 end up in the first bucket, keys between 10-20 end up in the second bucket, and keys 0-10 end up in the third bucket. We use the term buckets and partitions interchangeably.

##Prepare Dataset

Here we generate the data which we will use throughout the rest of this notebook. This is a toy dataset with 30 records, and consists of two fields in each record, separated by a tab character. The first field contains random integers between 1 and 30 (a hypothetical word count), and the second field contains English words. The goal is to sort the data by word count from highest to lowest.


```Python
%%writefile generate_numbers.py
#!/usr/bin/Python
words = ["cell","center","change","cluster","clustering","code","computational","compute","computing","consists",\
         "contour","corresponding","creating","current","dataset","def","descent","descent","develop","different",\
         "distributed","do","document","done","driver","drivers","during","efficient","evaluate","experiements"]
import random
N = 30
for n in range(N):
    print random.randint(0,N),"\t",words[n]
```

Overwriting generate_numbers.py



```Python
# give the Python file exacutable permissions, write the file, and inspect number of lines
!chmod +x generate_numbers.py;
!./generate_numbers.py > generate_numbers.output
!wc -l generate_numbers.output
```

30 generate_numbers.output



```Python
# view the raw dataset
!cat generate_numbers.output
```

    1   cell
    14  center
    9   change
    2   cluster
    10  clustering
    24  code
    15  computational
    25  compute
    15  computing
    19  consists
    7   contour
    22  corresponding
    27  creating
    0   current
    28  dataset
    26  def
    26  descent
    23  descent
    4   develop
    3   different
    5   distributed
    30  do
    15  document
    24  done
    27  driver
    17  drivers
    9   during
    13  efficient
    19  evaluate
    27  experiements


##Section I - Understanding Unix Sort

###Importance of Unix Sort

Sort is a simple and very useful command found in Unix systems. It rearranges lines of text numerically and/or alphabetically. Hadoop Streaming's KeyBasedComparator is modeled after Unix sort, and utilizes command line options which are the same as Unix sort command line options.

###Unix Sort Overview

~~~~
# sort syntax
sort [OPTION]... [FILE]...
~~~~

Sort treats a single line of text as a single datum to be sorted. It operates on fields (by default, the whole line is considered a field). It uses tabs as the delimiter by default (which can be configured with -t option), and splits a (non-empty) line into one or more parts, whereby each part is considered a field. Each field is identified by its index (POS).

The most important configuration option is perhaps -k or --key. Start a key at POS1 (origin 1), end it at POS2 (default end of line):
'-k, --key=POS1[,POS2]'

For example, -k1 (without ending POS), produces a key that is the whole line, and -k1,1 produces a key that is the first field. Multiple -k options can be supplied, and applied left to right. Sort keys can be tricky sometimes, and should be treated with care. For example:

~~~~
sort –k1 –k2,2n
# Will not work properly, as -k1 uses the whole line as key, and trumps -k2,2n.
# Another example:
sort -k2 -k3
# This is redundant: it's equivalent to sort -k2.
~~~~

A good practice for supplying multiple sort keys is to make sure they are non-overlapping.

Other commonly used flags/options are: -n, which sorts the keys numerically, and -r which reverses the sort order.

###sort examples

<pre><span class="o">%%</span><span class="k">writefile</span> Unix-sort-example.txt
Unix,30
Solaris,10
Linux,25
Linux,20
HPUX,100
AIX,25
</pre>

(Source: [http://www.theUnixschool.com/2012/08/linux-sort-command-examples.html](http://www.theUnixschool.com/2012/08/linux-sort-command-examples.html))


```Python
%%writefile Unix-sort-example.txt
Unix,30
Solaris,10
Linux,25
Linux,20
HPUX,100
AIX,25
```

Overwriting Unix-sort-example.txt


<div id="tab-container" class="tab-container">
  <ul class='etabs'>
    <li class='tab'><a href="#tabs1-html">HTML Markup</a></li>
    <li class='tab'><a href="#tabs1-js">Required JS</a></li>
    <li class='tab'><a href="#tabs1-css">Example CSS</a></li>
  </ul>
  <div id="tabs1-html">
    Sort by field 1 (default alphabetically), deliminator ","    
    <table>
    <tr>
    <td><pre>cat Unix-sort-example.txt</pre></td>
    <td><pre>sort -t"," -k1,1 Unix-sort-example.txt</pre></td>
    </tr>
    <tr>
    <td><pre>
    Unix,30
    Solaris,10
    Linux,25
    Linux,20
    HPUX,100
    AIX,25
    </pre></td>
    <td><pre>
    AIX,25
    HPUX,100
    Linux,20
    Linux,25
    Solaris,10
    Unix,30
    </pre></td>
    </tr>
    </table>
  </div>
  <div id="tabs1-js">
    Sort by field 2 numerically reverse, deliminator ","   
    <table>
    <tr>
    <td><pre>cat Unix-sort-example.txt</pre></td>
    <td><pre>sort -t"," -k2,2nr  Unix-sort-example.txt</pre></td>
    </tr>
    <tr>
    <td><pre>
    Unix,30
    Solaris,10
    Linux,25
    Linux,20
    HPUX,100
    AIX,25
    </pre></td>
    <td><pre>
    HPUX,100
    Unix,30
    AIX,25
    Linux,25
    Linux,20
    Solaris,10
    </pre></td>
    </tr>
    </table>
  </div>
  <div id="tabs1-css">
    Sort by field 1 alphabetically first, then by field 2 numeric reverse   
    <table>
    <tr>
    <td><pre>cat Unix-sort-example.txt</pre></td>
    <td><pre>sort -t"," -k1,1 -k2,2nr  Unix-sort-example.txt</pre></td>
    </tr>
    <tr>
    <td><pre>
    Unix,30
    Solaris,10
    Linux,25
    Linux,20
    HPUX,100
    AIX,25
    </pre></td>
    <td><pre>
    AIX,25
    HPUX,100
    Linux,25
    Linux,20
    Solaris,10
    Unix,30
    </pre></td>
    </tr>
    </table>
  </div>
</div>

##Section II - Hadoop Streaming

###II.A. Hadoop's Default Sorting Behavior

Key points:
* By default, Hadoop performs a partial sort on mapper output keys, i.e. within each partition keys are sorted.
* By default, keys are sorted as strings.
    * When processing a mapper output record, first the partitioner decides which partition the record should be sent to.
    * In shuffle and sort stage, keys within a partition are sorted.
* If there is only one partition, mapper output keys will be sorted in total order.
* The partition index of a given key from mapper outputs is determined by the partitioner, the  default partitioner is `HashPartitioner` which relies on `java`'s `hashCode` function to compute an integer hash for the key. The partition index is derived next by hash modulo number of reducers.

###II.B. Hadoop Streaming parameters

Hadoop streaming can be further fine-grain controlled through the command line options below. Through these, we can fine-tune the Hadoop framework to better understand line-oriented record structure, and achieve the versatility of single-machine Unix sort, but in a distributed and efficient manner.

~~~~
stream.num.map.output.key.fields
stream.map.output.field.separator
mapreduce.partition.keypartitioner.options
KeyFieldBasedComparator
keycomparator.options
partitioner org.apache.Hadoop.mapred.lib.KeyFieldBasedPartitioner
~~~~

In a sorting task, Hadoop Streaming provides the same interface as Unix sort. Both consume a stream of lines of text, and produce a permutation of input records, based on one or more sort keys extracted from each line of input. Without customizing its sorting and partitioning, Hadoop Streaming treats implicitly each input line as a record consisting of a single key and value, separated by a "tab" character.

Just like the various options Unix sort offers, Hadoop Streaming can be customized to use multiple fields for sorting, sort records by numeric order or keys and sort in reverse order.

The following table provides an overview of relationships between Hadoop Streaming sorting and Unix sort:

<table>
<tr>
    <th></th>
    <th>Unix `sort`   </th>
    <th>Hadoop streaming</th>
</tr>
<tr>
    <td>Key Field Separator</td>
    <td>`-t`</td>
    <td>`-D stream.map.output.field.separator`</td>
</tr>
<tr>
    <td>Number of Key Fields</td>
    <td>Not Required</td>
    <td>`-D stream.num.map.output.key.fields`</td>
</tr>
<tr>
    <td>Key Range</td>
    <td>`-k, --key=POS1[,POS2]`</td>
    <td>`-D mapreduce.partition.keycomparator.options`   (same syntax as Unix sort)</td>
</tr>
<tr>
    <td>Numeric Sort</td>
    <td>`-n, --numeric-sort`</td>
    <td>`-D mapreduce.partition.keycomparator.options`   (same syntax as Unix sort)</td>
</tr>
<tr>
    <td>Reverse Order</td>
    <td>`-r --reverse`</td>
    <td>`-D mapreduce.partition.keycomparator.options`   (same syntax as Unix sort)</td>
</tr>
<tr>
    <td>Partitioner Class</td>
    <td>Not Applicable</td>
    <td>`-partitioner org.apache.Hadoop.mapred.lib.KeyFieldBasedPartitioner`</td>
</tr>
<tr>
    <td>Comparator Class</td>
    <td>Not Applicable</td>
    <td>`-D mapreduce.job.output.key.comparator.class`</td>
</tr>
<tr>
    <td>Partition Key Fields</td>
    <td>Not Applicable</td>
    <td>`-D mapreduce.partition.keypartitioner.options`</td>
</tr>
</table>

Therefore, given a distributed sorting problem, it is always helpful to start with a non-scalable solution that can be provided by Unix sort and work out the required Hadoop Streaming configurations from there.

####Configure Hadoop Streaming: Prerequisites

~~~~
-D mapreduce.job.output.key.comparator.class=org.apache.Hadoop.mapreduce.lib.partition.KeyFieldBasedComparator \
-partitioner org.apache.Hadoop.mapred.lib.KeyFieldBasedPartitioner
~~~~

These two options instruct Hadoop Streaming to use two specific Hadoop Java library classes: `KeyFieldBasedComparator` and `KeyFieldBasedPartitioner`. They come in standard Hadoop distribution, and provide the required machinery.

####Configure Hadoop Streaming: Step 1

Specify number of key fields and key field seperator:

~~~~
 -D stream.num.map.output.key.fields=4 \
 -D mapreduce.map.output.key.field.separator=.
~~~~

In Unix sort when input lines use a non-tab delimiter, we need to supply the -t _separator_ option. Similarly in Hadoop streaming, we need to specify the character to use as key separators. Common options include: comma",", period ".", and space " ".

One additional hint to Hadoop is the number of key fields, which is not required for Unix sort. This helps Hadoop streaming to only parse the relevant parts of input lines, as in the end only keys are sorted (not values) – therefore, Hadoop can avoid performing expensive parsing and sorting on value parts of input line records.

####Configure Hadoop Streaming: Step 2

~~~~
# Specify sorting options
-D mapreduce.partition.keycomparator.options=-k2,2nr
~~~~

This part is very straightforward. Whatever one would do with Unix sort (eg. -k1,1 -k3,4nr), just mirror it for Hadoop streaming. However it is crucial to remember that Hadoop only uses `KeyFieldBasedComparator` to sort records within partitions. Therefore, this step only helps achieve partial sort.

####Configure Hadoop Streaming: Step 3

~~~~
# Specify partition key field
-D mapreduce.partition.keypartitioner.options=-k1,1
~~~~

In this step, we need to specify which key field to use for partitioning. There's no equivalent in Unix sort. One critical detail to keep in mind is that, even though Hadoop streaming uses Unix sort --key option's syntax for mapreduce.partition.keypartitioner.options., no sorting will actually be performed. It only uses expressions such as -k2,2nr for key extraction; the nr flags will be ignored.

In later sections (Partitioning in MRJob), we will discuss in detail how to incorporate sorting into the partitioner by custom partition key construction.

####Summary of Common Practices for Sorting Related Configuration

<div style="background: #ffffff; overflow:auto;width:auto;border:0;border-width:.0;padding:.0;"><pre style="margin: 0; line-height: 125%">  -partitioner org.apache.Hadoop.mapred.lib.KeyFieldBasedPartitioner
  -D mapreduce.job.output.key.comparator.class=org.apache.Hadoop.mapreduce.lib.partition.KeyFieldBasedComparator \
  -D stream.num.map.output.key.fields=<span style="color: #0000FF">4</span> \
  -D map.output.key.field.separator=. \
  -D mapreduce.partition.keypartitioner.options=<span style="color: #0000FF">-k1,2</span> \
  -D mapreduce.job.reduces=<span style="color: #0000FF">12</span> \
</pre></div>

At bare minimum, we typically need to specify:

1. Use KeyFieldBasedPartitioner
2. Use KeyFieldBasedComparator
3. Key field separator (can be omitted if TAB is used as separator)
4. Number of key fields
5. Key field separator again for mapper (under a different config option)
6. Partitioner options (Unix sort syntax)
7. Number of reducer jobs

(See Hadoop streaming official documentation for more information (Hadoop version = 2.7.2.)
Side-by-side Examples: Unix sort vs. Hadoop streaming:


<table>
<tbody>
<tr>
<td>Unix sort</td>
<td>Hadoop Streaming</td>
</tr>
<tr>
<td>
<code>sort -t"," -k1,1</code>
</td>
<td>
<code>
-D mapreduce.job.output.key.comparator.class=\
  org.apache.Hadoop.mapreduce.lib.partition.KeyFieldBasedComparator \
-D stream.num.map.output.key.fields=2 \
-D stream.map.output.field.separator="," \
-D mapreduce.partition.keypartitioner.options=-k1,1\
-partitioner org.apache.Hadoop.mapred.lib.KeyFieldBasedPartitioner\
</code>
</td>
</tr>

<tr>
<td>
<code>
sort -k1,1 -k2,3nr
</code>
</td>
<td>
<code>
-D mapreduce.job.output.key.comparator.class=\
  org.apache.Hadoop.mapreduce.lib.partition.KeyFieldBasedComparator \
-D stream.num.map.output.key.fields=3 \
-D mapreduce.partition.keypartitioner.options="-k1,1 -k2,3nr"\
-partitioner org.apache.Hadoop.mapred.lib.KeyFieldBasedPartitioner\
-D mapreduce.job.reduces=1
</code>
</td>
</tr>
</tbody>
</table>


###II.C. Hadoop Streaming Implementation

You will need to install, configure, and start Hadoop. Brief instructions follow, but detailed instructions are beyond the scope of this notebook.

####Start Hadoop

To run the examples in this notebook you must download and configure Hadoop on your local computer. Go to http://Hadoop.apache.org/ for the latest downloads. 

Everything you need to get up and running can be found on this page: [https://Hadoop.apache.org/docs/r2.7.2/Hadoop-project-dist/Hadoop-common/SingleCluster.html](https://Hadoop.apache.org/docs/r2.7.2/Hadoop-project-dist/Hadoop-common/SingleCluster.html). There are also many websites with specialized instructions.

Once all components have been downloaded and istalled, you can check that everything is running by running the `jps` command in your terminal. You should see output like this:
```
localhost:~ $ jps
83360 NodeManager
82724 DataNode
82488 NameNode
82984 SecondaryNameNode
83651 Jps
83118 ResourceManager
83420 JobHistoryServer
```

This notebook runs on the following setup:
```
Mac OX Yosemite 10.10.5
java version "1.7.0_51"
Hadoop version 2.7.2
```



```Python
# should you need to regenerate the file and put it in hdfs a second time, make sure to delete the existing file first:
!hdfs dfs -rm -r /user/koza/sort
```

    16/08/20 19:24:29 INFO fs.TrashPolicyDefault: Namenode trash configuration: Deletion interval = 0 minutes, Emptier interval = 0 minutes.
    Deleted /user/koza/sort



```Python
# put the file in hdfs:
!hdfs dfs -mkdir /user/koza/sort
!hdfs dfs -mkdir /user/koza/sort/output
!hdfs dfs -put generate_numbers.output /user/koza/sort
```


```Python
# make sure it's really there:
!hdfs dfs -ls /user/koza/sort/generate_numbers.output
```

    -rw-r--r--   1 koza supergroup        486 2016-08-20 19:17 /user/koza/sort/generate_numbers.output

####II.C.1. Hadoop Streaming Implementation - single reducer

Key points:
* Single reducer guarantees a single partition
* Partial sort becomes total sort
* No need for secondary sorting
* Single Reducer becomes scalability bottleneck

####Steps

In the mapper shuffle sort phase, the data is sorted by the primary key, and sent to a single reducer. By specifying /bin/cat/ for the mapper and reducer, we are telling Hadoop streaming to use the identity mapper and reducer which simply output the input (Key,Value) pairs.

####Setup

```
-D stream.num.map.output.key.fields=2 
-D stream.map.output.field.separator="\t" 
-D mapreduce.partition.keycomparator.options="-k1,1nr -k2,2" 
```

First we'll specify the number of keys, in our case, 2. The count and the word are primary and secondary keys, respectively. Next we'll tell Hadoop streaming that our field separator is a tab character. Lastly we'll use the keycompartor options to specify which keys to use for sorting. Here, -n specifies that the sorting is numerical for the primary key, and -r specifies that the result should be reversed, followed by k2 which will sort the words alphabetically to break ties. Refer to the Unix sort section above.

<span style="color:red"><strong>IMPORTANT:</strong></span> Hadoop streaming is particular about the order in which options are specified.  

(For more information, see the docs here: [https://Hadoop.apache.org/docs/r1.2.1/streaming.html#Hadoop+Comparator+Class](https://Hadoop.apache.org/docs/r1.2.1/streaming.html#Hadoop+Comparator+Class).)

```Python
!hdfs dfs -rm -r /user/koza/sort/output
!Hadoop jar /usr/local/Cellar/Hadoop/2.7.2/libexec/share/Hadoop/tools/lib/Hadoop-streaming-2.7.2.jar \
-D mapred.output.key.comparator.class=org.apache.Hadoop.mapred.lib.KeyFieldBasedComparator \
-D stream.num.map.output.key.fields=2 \
-D stream.map.output.field.separator="\t" \
-D mapreduce.partition.keycomparator.options="-k1,1nr -k2,2" \
-mapper /bin/cat \
-reducer /bin/cat \
-input /user/koza/sort/generate_numbers.output -output /user/koza/sort/output \

```


```Python
# Check to see that we have indeed generated a single output file
!hdfs dfs -ls /user/koza/sort/output
```

    Found 2 items
    -rw-r--r--   1 koza supergroup          0 2016-08-20 19:18 /user/koza/sort/output/_SUCCESS
    -rw-r--r--   1 koza supergroup        516 2016-08-20 19:18 /user/koza/sort/output/part-00000



```Python
# Print the results
print "="*100
print "Single Reducer Sorted Output - Hadoop Streaming"
print "="*100
!hdfs dfs -cat /user/koza/sort/output/part-00000
```

    ====================================================================================================
    Single Reducer Sorted Output - Hadoop Streaming
    ====================================================================================================
    30      do  
    28      dataset 
    27      driver  
    27      creating    
    27      experiements    
    26      def 
    26      descent 
    25      compute 
    24      done    
    24      code    
    23      descent 
    22      corresponding   
    19      evaluate    
    19      consists    
    17      drivers 
    15      computing   
    15      document    
    15      computational   
    14      center  
    13      efficient   
    10      clustering  
    9       during  
    9       change  
    7       contour 
    5       distributed 
    4       develop 
    3       different   
    2       cluster 
    1   cell    
    0       current 



###II.C.2. Hadoop Streaming Implementation - multiple reducers

####Keypoints:

* Need to guarantee that every key in a single reducer is to be "pre-sorted" against all other reducers.
* Requires knowledge of the distribution of values to be sorted - more about this later in the sampling section.
* Uses secondary sort to order keys within each partition.

####What's new:

Now the mapper needs to emit an additional key for each record by which to partition. We partition by this new 'primary' key and sort by secondary and tertiary keys to break ties. Here, the partition key is the primary key.

The following diagram illustrates the steps required to perform total sort in a multi-reducer setting:
 
![Total Order Sort, Step 1](images/02ts-steps2.png)

Figure 2. Total Order sort with multiple reducers


###Multiple Reducer Overview

After the Map phase and before the beginning of the Reduce phase there is a handoff process, known as shuffle and sort. Output from the mapper tasks is prepared and moved to the nodes where the reducer tasks will be run. To improve overall efficiency, records from mapper output are sent to the physical node that a reducer will be running on as they are being produced - to avoid flooding the network when all mapper tasks are complete.

What this means is that when we have more than one reducer in a MapReduce job, Hadoop no longer sorts the keys globally (total sort). Instead mapper outputs are partitioned while they're being produced, and before the reduce phase starts, records are sorted by the key within each partition. In other words, Hadoop's architecture only guarantees partial sort.

Therefore, to achieve total sort, a programmer needs to incorporate additional steps and supply the Hadoop framework additional aid during the shuffle and sort phase. Particularly, a partition file or partition function is required.

####Modification 1: Include a partition file or partition function inside mappers

Recall that we can use an identity mapper in single-reducer step up, which just echos back the (key, value) pair from input data. In a multi-reducer setup, we will need to add an additional "partition key" to instruct Hadoop how to partition records, and pass through the original (key, value) pair.
The partition key is derived from the input key, with the help of either a partition file (more on this in the sampling section) or a user-specified partition function, which takes a key as input, and produces a partition key. Different input keys can result in same partition key.

####Modification 2: Drop internal partition key inside reducers

Now we have two keys (as opposed to just one), and one is used for partitioning, the other is used for sorting. The reducer needs to drop the partition key which is used internally to aid total sort, and recover the original (key, value) pairs.

####Modification 3: Post-processing step to order partitions

The MapReduce job output is written to HDFS, with the output from each partition in a separate file (usually named something such as: part-00000). These file names are indexed and ordered. However, Hadoop makes no attempt to sort partition keys – the mapping between partition key and partition index is not order-preserving. 

Therefore, while partition keys key_1, key_2 and key_1 < key_2, it's possible that the output of partition with key_1 could be written to file part-00006 and the output of partition with key_2 written to file part-00003.
Therefore, a post-processing step is required to finish total sort. We will need to take one record from every (non-empty) partition output, sort them, and construct the appropriate ordering among partitions.

####Introductory Example

Consider the task of sorting English words alphabetically, for example, four words from our example dataset:

<code>
experiments    
def    
descent    
compute 
</code>  

The expected sorted output is:

<code>
compute    
def    
descent    
experiments    
</code>

We can use the first character from each word as a partition key. The input data could potentially have billions of words, but we will never have more than 26 unique partition keys (assuming all words are lowercase). In addition, a word starting with "a" will always have a lower alphabetical ordering compared to a word which starts with "z". Therefore, all words belonging to partition "a" will be "pre-sorted" against all words from partition "z". The technique described here is equivalent to the following partition function:

<code>
def partition_function(word):
    assert len(word) > 0
    return word[0]
</code>

It is important to note that a partition function must preserve sort order, i.e. all partitions need to be sorted against each other. For instance, the following partition function is not valid (in sorting words in alphabetical order):

<code>
def partition_function(word):
    assert len(word) > 0
    return word[-1]
</code>

The mapper output or the four words with this partition scheme is:

~~~~
e    experiments    
d    def    
d    descent    
c    compute
~~~~

The following diagram outlines the flow of data with this example:

![Total Order Sort, Step 1](images/03-partition.png) 

Figure 3. Partial Order Sort

Note that partition key "e" maps to partition 0, even if it is "greater than" key "d" and "c". This illustrates that the mapping between partition key and partition indices are not order preserving. In addition, sorting within partitions is based on the original key (word itself).

###Implementation Walkthrough

Coming back to our original dataset, here we will sort and print the output in two steps. Step one will partition and sort the data, and step two will arrange the partitions in the appropriate order. In the MRJob implementation that follows, we'll build on this and demonstrate how to ensure that the partitions are created in the appropriate order to begin with.

1. Run the Hadoop command that prepends an alphabetic key to each row such that they end up in the appropriate partition, shuffle, and sort.
2. Combine the secondary sort output files in the appropriate order

####Setup

The following options comprise the Hadoop Streaming configuration for the sort job. Notice the addition of the keypartitioner option, which tells Hadoop Streaming to partition by the primary key. Remember that the order of the options is important.

~~~~
-D stream.num.map.output.key.fields=3 \
-D stream.map.output.field.separator="\t" \
-D mapreduce.partition.keypartitioner.options=-k1,1 \
-D mapreduce.job.output.key.comparator.class=org.apache.Hadoop.mapred.lib.KeyFieldBasedComparator \
-D mapreduce.partition.keycomparator.options="-k1,1 -k2,2nr -k3,3" \
~~~~

Here, "-k1,1 -k2,2nr -k3,3" performs secondary sorting. Our three key fields are:
* -k1,1: partition key, one of {A,B,C}{A,B,C} (this part is optional, since each partition will contain the same partition key).
* -k2,2nr: input key (number/count), and we specify nr flags to sort them numerically reverse.
* -k3,3 : input value (word), if two records have same count, we break the tie by comparing the words alphabetically.

The following mapper is an identity mapper with a partition function included; it prepends an alphabetic key as partition key to input records.

### Function to prepend an alphabetic key to each row such that they end up in the appropriate partition

The following mapper is an identity mapper with a partition function included, it prepends an alphabetic key as partition key to input records.


```Python
%%writefile prependPartitionKeyMapper.py
#!/usr/bin/env Python
import sys
for line in sys.stdin:
    line = line.strip()
    key, value = line.split("\t")
    if int(key) < 10:
        print "%s\t%s\t%s" % ("A", key, value)   
    elif int(key) < 20:
        print "%s\t%s\t%s" % ("B", key, value)   
    else:
        print "%s\t%s\t%s" % ("C", key, value)    
```

    Overwriting prependPartitionKeyMapper.py


### __Step 1 - run the Hadoop command specifying 3 reducers, the partition key, and the sort keys__


```Python
!hdfs dfs -rm -r /user/koza/sort/secondary_sort_output
!Hadoop jar /usr/local/Cellar/Hadoop/2.7.2/libexec/share/Hadoop/tools/lib/Hadoop-streaming-2.7.2.jar \
    -D stream.num.map.output.key.fields=3 \
    -D stream.map.output.field.separator="\t" \
    -D mapreduce.partition.keypartitioner.options=-k1,1 \
    -D mapreduce.job.output.key.comparator.class=org.apache.Hadoop.mapred.lib.KeyFieldBasedComparator \
    -D mapreduce.partition.keycomparator.options="-k1,1 -k2,2nr -k3,3" \
    -mapper prependPartitionKeyMapper.py \
    -reducer /bin/cat \
    -file prependPartitionKeyMapper.py -input /user/koza/sort/generate_numbers.output \
    -output /user/koza/sort/secondary_sort_output \
    -numReduceTasks 3 \
    -partitioner org.apache.Hadoop.mapred.lib.KeyFieldBasedPartitioner 
```

<h3> Check the output </h3>


```Python
!hdfs dfs -ls /user/koza/sort/secondary_sort_output
print "="*100
print "/part-00000"
print "="*100
!hdfs dfs -cat /user/koza/sort/secondary_sort_output/part-00000
print "="*100
print "/part-00001"
print "="*100
!hdfs dfs -cat /user/koza/sort/secondary_sort_output/part-00001
print "="*100
print "/part-00002"
print "="*100
!hdfs dfs -cat /user/koza/sort/secondary_sort_output/part-00002
```

    Found 4 items
    -rw-r--r--   1 koza supergroup          0 2016-08-20 19:25 /user/koza/sort/secondary_sort_output/_SUCCESS
    -rw-r--r--   1 koza supergroup        141 2016-08-20 19:25 /user/koza/sort/secondary_sort_output/part-00000
    -rw-r--r--   1 koza supergroup        164 2016-08-20 19:25 /user/koza/sort/secondary_sort_output/part-00001
    -rw-r--r--   1 koza supergroup        118 2016-08-20 19:25 /user/koza/sort/secondary_sort_output/part-00002
    ====================================================================================================
    /part-00000
    ====================================================================================================
    B   19  consists    
    B   19  evaluate    
    B   17  drivers 
    B   15  computational   
    B   15  computing   
    B   15  document    
    B   14  center  
    B   13  efficient   
    B   10  clustering  
    ====================================================================================================
    /part-00001
    ====================================================================================================
    C   30  do  
    C   28  dataset 
    C   27  creating    
    C   27  driver  
    C   27  experiements    
    C   26  def 
    C   26  descent 
    C   25  compute 
    C   24  code    
    C   24  done    
    C   23  descent 
    C   22  corresponding   
    ====================================================================================================
    /part-00002
    ====================================================================================================
    A   9   change  
    A   9   during  
    A   7   contour 
    A   5   distributed 
    A   4   develop 
    A   3   different   
    A   2   cluster 
    A   1   cell    
    A   0   current 


### Step 2 - Combine the sorted output files in the appropriate order
The following code block peaks at the first line of each partition file to determine order of partitions, and prints the contents of each partition in order of largest to smallest.  Notice that while the files are arranged in total order, the partition file names are not ordered. In the MRJob implementation, we'll tackle this issue.


```Python
# The subprocess module allows you to spawn new (system) processes, connect to their input/output/error pipes, 
# and obtain their return codes. Ref: https://docs.Python.org/2/library/subprocess.html
import subprocess 
import re



'''
subprocess.Popen()
Opens a new subprocess and executes the Unix command in the args array, passing the output to STDOUT.
This is the equivalent of typing: 
hdfs dfs -ls /user/koza/sort/secondary_sort_output/part-*
in the Unix shell prompt
Even though we cannot see this output it would look like this:
-rw-r--r--   1 koza supergroup        141 2016-08-20 19:25 /user/koza/sort/secondary_sort_output/part-00000
-rw-r--r--   1 koza supergroup        164 2016-08-20 19:25 /user/koza/sort/secondary_sort_output/part-00001
-rw-r--r--   1 koza supergroup        118 2016-08-20 19:25 /user/koza/sort/secondary_sort_output/part-00002
'''

p = subprocess.Popen(["hdfs", "dfs", "-ls", "/user/koza/sort/secondary_sort_output/part-*" ],  
                     stdout=subprocess.PIPE, stderr=subprocess.STDOUT)


'''
Save the output of the above command to the 'lines' string variable by reading each line and appending it to the 'lines' string.
The resulting lines string should look like this:

'-rw-r--r--   1 koza supergroup        141 2016-08-20 19:25 /user/koza/sort/secondary_sort_output/part-00000\n-rw-r--r--   1 koza supergroup        164 2016-08-20 19:25 /user/koza/sort/secondary_sort_output/part-00001\n-rw-r--r--   1 koza supergroup        118 2016-08-20 19:25 /user/koza/sort/secondary_sort_output/part-00002\n'

'''
lines=""
for line in p.stdout.readlines():
    lines = lines + line

    
'''
The following regular expresion extracts the paths from 'lines', and appends each path to the outputPARTFiles list.
The resulting outputPARTFiles list should look like this:

['/user/koza/sort/secondary_sort_output/part-00000',
 '/user/koza/sort/secondary_sort_output/part-00001',
 '/user/koza/sort/secondary_sort_output/part-00002']

'''    
regex = re.compile('(\/user\/koza\/sort\/secondary_sort_output\/part-\d*)')
it = re.finditer(regex, lines)

outputPARTFiles=[]
for match in it:
    outputPARTFiles.append(match.group(0))

'''
Next is where we peek at the first line of each file and extract the key. The resulting partKeys list should look like this:
[19, 30, 9]

For each file f in outputPARTFiles
    int(...)                            <-- this will convert the key returned by the commands that follow to an integer
    ["hdfs", "dfs", "-cat", f]          <-- cat the file 
    stdout=subprocess.PIPE              <-- to STDOUT
    stdout.read()                       <-- read the STDOUT into memory
    splitlines()[0]                     <-- split that output into lines, and return the first line (at index 0)
    split('\t')[1]                      <-- split that first line by tab character, and return the item at index 1 - this is the 'key'
    strip()                             <-- remove trailing and leading spaces so output is clean

'''    
partKeys=[]
for f in outputPARTFiles:
    partKeys.append(int(subprocess.Popen(["hdfs", "dfs", "-cat", f], 
                             stdout=subprocess.PIPE).stdout.read().splitlines()[0].split('\t')[1].strip()))

    
'''
create a dict d assoicating each key with its corresponding file path. The resulting d dict should look like this:

{9: '/user/koza/sort/secondary_sort_output/part-00002',
 19: '/user/koza/sort/secondary_sort_output/part-00000',
 30: '/user/koza/sort/secondary_sort_output/part-00001'}
 
 ^^ we now know that the largest key lives in part-00001, and we will display that file content first

'''    
d={}
for i in range(len(outputPARTFiles)):
    print "part is %d, key is %d, %s" %(i, partKeys[i], outputPARTFiles[i])
    d[partKeys[i]] = outputPARTFiles[i]

'''
Print the contents of each file in total sorted order, by sorting the d dict by key in reverse:

    sorted(d.items(), key=lambda x: x[0], reverse=True)   <-- sorts d dict by key x[0], in reverse
    print "%d:%s"%(k[0], k[1])                            <-- print the key k[0] and the path k[1]
    
    use a subprocess to read the contents of each file listed in d (k[1]) (see explanation above for subprocess)
    and print each line omitting leading and trailing spaces
'''
    
#TOTAL Sort in decreasing order
for k in sorted(d.items(), key=lambda x: x[0], reverse=True):
    print "="*100
    print "%d:%s"%(k[0], k[1])
    print "="*100
    p = subprocess.Popen(["hdfs", "dfs", "-cat", k[1]],  stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    for line in p.stdout.readlines():
        print line.strip()

```

    part is 0, key is 19, /user/koza/sort/secondary_sort_output/part-00000
    part is 1, key is 30, /user/koza/sort/secondary_sort_output/part-00001
    part is 2, key is 9, /user/koza/sort/secondary_sort_output/part-00002
    ====================================================================================================
    30:/user/koza/sort/secondary_sort_output/part-00001
    ====================================================================================================
    C   30  do
    C   28  dataset
    C   27  creating
    C   27  driver
    C   27  experiements
    C   26  def
    C   26  descent
    C   25  compute
    C   24  code
    C   24  done
    C   23  descent
    C   22  corresponding
    ====================================================================================================
    19:/user/koza/sort/secondary_sort_output/part-00000
    ====================================================================================================
    B   19  consists
    B   19  evaluate
    B   17  drivers
    B   15  computational
    B   15  computing
    B   15  document
    B   14  center
    B   13  efficient
    B   10  clustering
    ====================================================================================================
    9:/user/koza/sort/secondary_sort_output/part-00002
    ====================================================================================================
    A   9   change
    A   9   during
    A   7   contour
    A   5   distributed
    A   4   develop
    A   3   different
    A   2   cluster
    A   1   cell
    A   0   current


##Section III - MRJob

For this section you will need the MRJob Python library. For installation instructions, go to: [https://github.com/Yelp/MRJob](https://github.com/Yelp/MRJob).

We'll first discuss a couple of key aspects of MRJob such as modes, protocols, and partitioning, before diving into the implementation. We'll also provide an illustrated example of partitioning.

###III.A. MRJob Modes

MRJob has three modes that correspond to different Hadoop environments.

####Local mode
Local mode simulates Hadoop Streaming, but does not require an actual Hadoop installation. This is great for testing out small jobs. However, local mode does not suport `'-k2,2nr'` type of sorting, i.e. sorting by numeric value, as it is not capable of Hadoop .jar library files (such as `KeyBasedComparator`). A workaround is to make sure numbers are converted to strings with a fixed length, and sorted by reverse order of their values. For positive integers, this can be done by: ``` sys.maxint - value ``` We include Local Mode implementation for completeness (see below).

####Hadoop mode
MRJob is capable of dispatching runners in a environment where Hadoop is installed. User-authored MRJob Python files are treated as shell scripts, and submitted to Hadoop streaming as MapReduce jobs. MRJob allows users to specify configurations supported by Hadoop streaming via the jobconf dictionary, either as part of MRStep or MRJob itself (which will be applied to all steps). The Python dictionary is serialized into command line arguments, and passed to the Hadoop streaming jar file. (See [https://pythonhosted.org/MRJob/guides/configs-Hadoopy-runners.html](https://pythonhosted.org/MRJob/guides/configs-Hadoopy-runners.html) for further documentation of Hadoop mode).

####EMR/Dataproc mode
In addition, MRJob supports running MapReduce jobs on a vendor-provided Hadoop runtime environment such as AWS Elastic MapReduce or Google Dataproc. The configuration and setup is very similar to Hadoop mode (-r Hadoop) with the following key differences:
* Vendor-specific credentials (such as AWS key and secret).
* Vendor-specific bootstrap process (for instance, configure Python version, and install third party libraries upon initialization).
* Use platform's native "step" management process (eg. AWS Steps).
* Use vendor-provided data storage and transportation (eg. use S3 for input/output on EMR).

The api surface for -r emr and -r Hadoop are almost identical, but the performance profiles can be drastically different.

###III.B. MRJob Protocols

At a high level, a `Protocol` is a gateway between the Hadoop Streaming world, and the MRJob/Python world. It translates raw bytes (text) into (key, value) pairs as some Python data structure, and vice versa. An MRJob protocol has the following interface: it is a class with a pair of functions `read` (which converts raw bytes to (key, value) pairs) and `write` which converts (key, value) pairs back to bytes/text:    

<!-- HTML generated using hilite.me --><div style="margin:20px 0; background: #f8f8f8; overflow:auto;width:auto;border:0;border-width:.0;padding:.0;"><pre style="margin: 0; line-height: 125%"><span style="color: #008000; font-weight: bold">class</span> <span style="color: #0000FF; font-weight: bold">Protocol</span>(<span style="color: #008000">object</span>):
    <span style="color: #008000; font-weight: bold">def</span> <span style="color: #0000FF">read</span>(<span style="color: #008000">self</span>, line):
        <span style="color: #008000; font-weight: bold">pass</span>
    <span style="color: #008000; font-weight: bold">def</span> <span style="color: #0000FF">write</span>(<span style="color: #008000">self</span>, key, value):
        <span style="color: #008000; font-weight: bold">pass</span>
</pre></div>
   
In addition, MRJob further abastracts away the differences between Python 2 and Python 3 (primarily in areas such as Unicode handling), and provides a unified interface across most Hadoop versions and Python versions.


When data enters MRJob components (mapper, reducer, combiner), the Protocol's (specified in MRJob class) read method is invoked, and supplies the (key, value) pair to the component. When data exits MRJob components, its Protocol's write method is invoked, converting (key, value) pair output from the component to raw bytes / text.

Consider a most generic MRJob job, consisting of one mapper step and one reduce step:

<!-- HTML generated using hilite.me -->
<div style="margin:20px 0; background: #f8f8f8; overflow:auto;width:auto;border:0;border-width:.0;padding:.0;"><pre style="margin: 0; line-height: 125%"><span style="color: #008000; font-weight: bold">class</span> <span style="color: #0000FF; font-weight: bold">GenericMRJob</span>(MRJob):

    <span style="color: #008000; font-weight: bold">def</span> <span style="color: #0000FF">mapper</span>(<span style="color: #008000">self</span>, key, value):
        <span style="color: #408080; font-style: italic"># mapper logic</span>
        <span style="color: #008000; font-weight: bold">yield</span> key, value
    <span style="color: #008000; font-weight: bold">def</span> <span style="color: #0000FF">reducer</span>(<span style="color: #008000">self</span>, key, values):
        <span style="color: #408080; font-style: italic"># reducer logic</span>
        <span style="color: #008000; font-weight: bold">yield</span> key, value
</pre></div>

There are four contact points between Python scripts and Hadoop streaming, which require three Protocols to be specified, as illustrated below.

![Total Order Sort, Step 1](images/04protocols.png)

Figure 4. Protocols diagram

####III.B.1 Types of Built-in Protocols

MRJob provides a number of built-in protocols, all of which can all be used for INPUT_PROTOCOL, INTERNAL_PROTOCOL or OUTPUT_PROTOCOL. By default, MRJob uses RawValueProtocol for INPUT_PROTOCOL, and JSONProtocol for INTERNAL_PROTOCOL and OUTPUT_PROTOCOL.

The table below lists four commonly used Protocols and their signature. Some key observations are:

* RawProtocol and RawValueProtocol do not attempt to serialize and deserialize text data.
* JSONProtocol and JSONValueProtocol use the JSON encoder and decoder to convert between text (stdin/stdout) and Python data structures (Python runtime).
* Value Protocols always treat key as None and do not auto insert a tab character in their write method.


<div id="protocol-tabs" class="tab-container">
  <ul class='etabs'>
    <li class='tab'><a href="#tabs1-rp">RawProtocol</a></li>
    <li class='tab'><a href="#tabs1-rvp">RawValueProtocol</a></li>
    <li class='tab'><a href="#tabs1-jp">JSONProtocol</a></li>
    <li class='tab'><a href="#tabs1-jvp">JSONValueProtocol</a></li>
  </ul>
  <div id="tabs1-rp">
    <table class="padded-table">
      <tr>
        <td colspan="4" style="background:#fff; vertical-align:middle;" width="50%">read</td>
        <td colspan="4" style="background:#fff; vertical-align:middle;" width="50%">write</td>
      </tr>
      <tr>
        <td colspan="2" class="color-start" width="25%"><strong>Source:</strong>  stdin</td>
        <td colspan="2" class="color-middle" width="25%"><strong>Target:</strong> Python</td>
        <td colspan="2" class="color-middle" width="25%"><strong>Source:</strong> Python</td>
        <td colspan="2" class="color-end" width="25%"><strong>Target:</strong> stdout</td>
      </tr>
      <tr>
        <td colspan="2" ><strong>Type:</strong> Text</td>
        <td colspan="2" ><strong>Type:</strong> <span style="color: #008000">tuple</span>( <span style="color: #008000">str</span>, <span style="color: #008000">str</span> )</td>
        <td colspan="2" ><strong>Type:</strong> <span style="color: #008000">tuple</span>( <span style="color: #008000">str</span>, <span style="color: #008000">str</span> )</td>
        <td colspan="2" ><strong>Type:</strong> Text</td>
      </tr>
      <tr>
        <td colspan="2"><strong>Shape:</strong> { Key }  [TAB]  { Value }</td>
        <td colspan="2"><strong>Shape:</strong> ( { Key }, { Value } )</td>
        <td colspan="2"><strong>Shape:</strong> ( { Key }, { Value } )</td>
        <td colspan="2"><strong>Shape:</strong> { Key }  [TAB]  { Value }</td>
      </tr>
      <tr>
        <td colspan="8" style="height:40px;vertical-align:bottom;"><strong>EXAMPLES</strong></td>
      </tr>
      <tr>
        <td colspan="4">word    <span style="color: #666666">12</span>  <span style="border: 1px solid #FF0000">⟶</span>  ( <span style="color: #BA2121">&quot;word&quot;</span>, <span style="color: #BA2121">&quot;12&quot;</span> )</td>
        <td colspan="4"><span style="color: #008000; font-weight: bold">yield</span> ( <span style="color: #BA2121">&quot;word&quot;</span>, <span style="color: #BA2121">&quot;12&quot;</span> )  <span style="border: 1px solid #FF0000">⟶</span>  word    <span style="color: #666666">12</span></td>
      </tr>
      <tr>
        <td colspan="4">line_with_no_tab  <span style="border: 1px solid #FF0000">⟶</span>  <span style="color:red">raise ValueError</span></td>
        <td colspan="4"><span style="color: #008000; font-weight: bold">yield</span> ( <span style="color: #BA2121">&quot;a</span><span style="color: #BB6622; font-weight: bold">\t</span><span style="color: #BA2121">b&quot;</span>, <span style="color: #BA2121">&quot;value&quot;</span> )  <span style="border: 1px solid #FF0000">⟶</span>  a     b    value</td>
      </tr>
      
      </table>

  </div>

  <div id="tabs1-rvp">
        <table class="padded-table">
      
      <tr>
        <td colspan="4" style="background:#fff; vertical-align:middle;"width="50%">read</td>
        <td colspan="4" style="background:#fff; vertical-align:middle;"width="50%">write</td>
      </tr>

      <tr>
        <td colspan="2" class="color-start" width="25%"><strong>Source:</strong> stdin</td>
        <td colspan="2" class="color-middle" width="25%"><strong>Target:</strong> Python</td>
        <td colspan="2" class="color-middle" width="25%"><strong>Source:</strong> Python</td>
        <td colspan="2" class="color-end" width="25%"><strong>Target:</strong> stdout</td>
      </tr>
      <tr>
        <td colspan="2"><strong>Type:</strong> Text</td>
        <td colspan="2"><strong>Type:</strong> <span style="color: #008000">tuple</span>( <span style="color: #008000">None</span>, <span style="color: #008000">str</span> )</td>
        <td colspan="2"><strong>Type:</strong> <span style="color: #008000">tuple</span>( <span style="color: #666666">\*</span>, <span style="color: #008000">str</span> )
</td>
        <td colspan="2"><strong>Type:</strong> Text</td>
      </tr>
      <tr>
        <td colspan="2"><strong>Shape:</strong> { Value }</td>
        <td colspan="2"><strong>Shape:</strong> ( None, { Value } )</td>
        <td colspan="2"><strong>Shape:</strong> ( { }, { Value } )</td>
        <td colspan="2"><strong>Shape:</strong> { Value }</td>
      </tr>
      <tr>
        <td colspan="8" style="height:40px;vertical-align:bottom;"><strong>EXAMPLES</strong></td>
      </tr>
      <tr>
        <td colspan="4">word    <span style="color: #666666">12</span>  <span style="border: 1px solid #FF0000">⟶</span>  ( <span style="color: #008000">None</span>, <span style="color: #BA2121">&quot;word</span><span style="color: #BB6622; font-weight: bold">\t</span><span style="color: #BA2121">12&quot;</span> )</td>
        <td colspan="4"><span style="color: #008000; font-weight: bold">yield</span> ( <span style="color: #BA2121">&quot;word&quot;</span>, <span style="color: #BA2121">&quot;12&quot;</span> )  <span style="border: 1px solid #FF0000">⟶</span>  <span style="color: #666666">12</span> <span style="color:#ff0055;padding-left:20px;">\*</span><span style="color:#777777;">(*see footnote*)</span></td>
      </tr>
      <tr>
        <td colspan="4">line_with_no_tab  <span style="border: 1px solid #FF0000">⟶</span>  ( <span style="color: #008000">None</span>, <span style="color: #BA2121">&quot;line_with_no_tab&quot;</span> )</td>
        <td colspan="4"><span style="color: #008000; font-weight: bold">yield</span> ( <span style="color: #008000">None</span>, <span style="color: #BA2121">&quot;a</span><span style="color: #BB6622; font-weight: bold">\t</span><span style="color: #BA2121">b&quot;</span> )  <span style="border: 1px solid #FF0000">⟶</span>  a    b</td>
      </tr>
      <caption align="bottom"><span style="color:#ff0055;">\*</span> "word" will be ommitted</caption>
      </table>
  </div>
  <div id="tabs1-jp">
        <table class="padded-table">
          <tr>
            <td colspan="4" style="background:#fff; vertical-align:middle;" width="50%">read</td>
            <td colspan="4" style="background:#fff; vertical-align:middle;" width="50%">write</td>
          </tr>
          <tr>
            <td colspan="2" class="color-start" width="25%"><strong>Source:</strong> stdin</td>
            <td colspan="2" class="color-middle" width="25%"><strong>Target:</strong> Python</td>
            <td colspan="2" class="color-middle" width="25%"><strong>Source:</strong> Python</td>
            <td colspan="2" class="color-end" width="25%"><strong>Target:</strong> stdout</td>
          </tr>
          <tr>
            <td colspan="2"><strong>Type:</strong> Text</td>
            <td colspan="2"><strong>Type:</strong> <span style="color: #008000">tuple</span>( <span style="color: #666666">\*</span>, <span style="color: #666666">\*</span> )</td>
            <td colspan="2"><strong>Type:</strong> <span style="color: #008000">tuple</span>( <span style="color: #666666">\*</span>, <span style="color: #666666">\*</span> )</td>
            <td colspan="2"><strong>Type:</strong> Text</td>
          </tr>
          <tr>
            <td colspan="2"><strong>Shape:</strong> { Key } [TAB] { Value }</td>
            <td colspan="2"><strong>Shape:</strong> ( { Key }, { Value } )</td>
            <td colspan="2"><strong>Shape:</strong> ( { Key }, { Value } )</td>
            <td colspan="2"><strong>Shape:</strong> { Key }  [TAB]  { Value }</td>
          </tr>
          <tr>
            <td colspan="8" style="height:40px;vertical-align:bottom;"><strong>EXAMPLES</strong></td>
          </tr>
          <tr>
            <td colspan="4"><span style="color: #666666">10</span>    [ <span style="color: #666666">1</span>, true, null, <span style="color: #BA2121">&quot;abc&quot;</span> ]    <span style="border: 1px solid #FF0000">⟶</span>    (<span style="color: #666666">10</span>, [ <span style="color: #666666">1</span>, <span style="color: #008000">True</span>, <span style="color: #008000">None</span>, <span style="color: #BA2121">&quot;abc&quot;</span> ] )</td>
            <td colspan="4"><span style="color: #008000; font-weight: bold">yield</span> ( [ <span style="color: #666666">1</span>, <span style="color: #666666">2</span> ], [ [ <span style="color: #666666">1</span>, <span style="color: #BA2121">&quot;3&quot;</span> ] ] )  <span style="border: 1px solid #FF0000">⟶</span> [ <span style="color: #666666">1</span>, <span style="color: #666666">2</span> ]    [ [<span style="color: #666666">1</span>, <span style="color: #BA2121">&quot;3&quot;</span>] ]</td>
          </tr>
          <tr>
            <td colspan="4">line_with_no_tab    <span style="border: 1px solid #FF0000">⟶</span>    <span style="color:red">raise ValueError</span></td>
            <td colspan="4"><span style="color: #008000; font-weight: bold">yield</span> <span style="color: #666666">1</span>, <span style="color: #666666">2</span>  <span style="border: 1px solid #FF0000">⟶</span>  <span style="color: #666666">1</span>    <span style="color: #666666">2</span></td>
          </tr>
      </table>
  </div>
    <div id="tabs1-jvp">
        <table class="padded-table">
          <tr>
            <td colspan="4" style="background:#fff; vertical-align:middle;" width="50%">read</td>
            <td colspan="4" style="background:#fff; vertical-align:middle;"width="50%">write</td>
          </tr>
          <tr>
            <td colspan="2" class="color-start" width="25%"><strong>Source:</strong> stdin</td>
            <td colspan="2" class="color-middle" width="25%"><strong>Target:</strong> Python</td>
            <td colspan="2" class="color-middle" width="25%"><strong>Source:</strong> Python</td>
            <td colspan="2" class="color-end" width="25%"><strong>Target:</strong> stdout</td>
          </tr>
          <tr>
            <td colspan="2"><strong>Type:</strong> Text</td>
            <td colspan="2"><strong>Type:</strong> <span style="color: #008000">tuple</span>( <span style="color: #666666">\*</span>, <span style="color: #666666">\*</span> )</td>
            <td colspan="2"><strong>Type:</strong> <span style="color: #008000">tuple</span>( <span style="color: #666666">\*</span>, <span style="color: #666666">\*</span> )
            </td>
            <td colspan="2"><strong>Type:</strong> Text</td>
          </tr>
          <tr>
            <td colspan="2"><strong>Shape:</strong> { Value }</td>
            <td colspan="2"><strong>Shape:</strong> ( <span style="color: #008000">None</span>, { Value } )
            </td>
            <td colspan="2"><strong>Shape:</strong> ( { }, { Value } )</td>
            <td colspan="2"><strong>Shape:</strong> { Value }
            </td>
          </tr>
          <tr>
            <td colspan="8" style="height:40px;vertical-align:bottom;"><strong>EXAMPLES</strong></td>
          </tr>
          <tr>
            <td colspan="4"><span style="color: #666666">10</span>    [ <span style="color: #666666">1</span>, true, null, <span style="color: #BA2121">&quot;abc&quot;</span> ] <span style="border: 1px solid #FF0000">⟶</span> <span style="color:red">raise ValueError</span></td>
            <td colspan="4"><!-- HTML generated using hilite.me --><span style="color: #008000; font-weight: bold">yield</span> ( <span style="color: #666666">1</span>, { <span style="color: #666666">2</span> : <span style="color: #666666">3</span> } )  <span style="border: 1px solid #FF0000">⟶</span>  { <span style="color: #BA2121">&quot;2&quot;</span> : <span style="color: #666666">3</span> }
</td>
          </tr>
          <tr>
            <td colspan="4">
            <!-- HTML generated using hilite.me -->{ <span style="color: #BA2121">&quot;value&quot;</span>: { <span style="color: #BA2121">&quot;nested&quot;</span>: [ <span style="color: #666666">1</span> ] } } <span style="border: 1px solid #FF0000">⟶</span> ( <span style="color: #008000">None</span>, { <span style="color: #BA2121">&quot;value&quot;</span>: { <span style="color: #BA2121">&quot;nested&quot;</span>: [ <span style="color: #666666">1</span> ] } } )
            </td>
            <td colspan="4"><!-- HTML generated using hilite.me --><span style="color: #008000; font-weight: bold">yield</span> ( [<span style="color: #666666">1</span>, <span style="color: #666666">2</span>, <span style="color: #666666">3</span> ], { <span style="color: #BA2121">&quot;value&quot;</span> : <span style="color: #666666">10</span> } ) <span style="border: 1px solid #FF0000">⟶</span>  { <span style="color: #BA2121">&quot;value&quot;</span> : <span style="color: #666666">10</span> }

            </td>
          </tr>
      </table>
  </div>
</div>


The following example further illustrates the behaviors of these protocols: a single line or text input (single line text file containing: 1001 {"value":10}) is fed to four different single step MapReduce jobs. Each uses one of the four Protocols discussed above as INPUT_PROTOCOL, INTERNAL_PROTOCOL and OUTPUT_PROTOCOL.

![Total Order Sort, Step 1](images/05recordlife.png)
 
Figure 5. Lifecycle of a single record.

Corresponding protocols code examples:

<div id="protocol-tabs" class="tab-container">
  <ul class='etabs'>
    <li class='tab'><a href="#tabs1-rp">RawProtocol</a></li>
    <li class='tab'><a href="#tabs1-rvp">RawValueProtocol</a></li>
    <li class='tab'><a href="#tabs1-jp">JSONProtocol</a></li>
    <li class='tab'><a href="#tabs1-jvp">JSONValueProtocol</a></li>
  </ul>

  <div id="tabs1-rp">
    <pre>
# raw_protocol.py

from MRJob.job import MRJob
from MRJob.protocol import RawProtocol

class RawProtocolExample(MRJob):

    INPUT_PROTOCOL = RawProtocol
    OUTPUT_PROTOCOL = RawProtocol
    INTERNAL_PROTOCOL = RawProtocol

    def mapper(self, key, value):
        assert key == "1001"
        assert value == "{\"value\":10}"
        yield "1001", "{\"value\":10}"
    def reducer(self, key, values):
        for value in values:
            assert key == "1001"
            assert value == "{\"value\":10}"
            yield "1001", "{\"value\":10}"

if __name__ == '__main__':
    RawProtocolExample.run()
    </pre>
  </div>

  <div id="tabs1-rvp">
        <pre>
# raw_value_protocol.py

from MRJob.job import MRJob
from MRJob.protocol import RawValueProtocol

class RawValueProtocolExample(MRJob):

    INPUT_PROTOCOL = RawValueProtocol
    OUTPUT_PROTOCOL = RawValueProtocol
    INTERNAL_PROTOCOL = RawValueProtocol

    def mapper(self, key, value):
        assert key == None
        assert value == "1001\t{\"value\":10}"
        yield "1001", "{\"value\":10}"
    def reducer(self, key, values):
        for value in values:
            assert key == None
            assert value == "{\"value\":10}"
            yield None, "{\"value\":10}"

if __name__ == '__main__':
    RawValueProtocolExample.run()
        </pre>
  </div>

  <div id="tabs1-jp">
        <pre>
# json_protocol.py

from MRJob.job import MRJob
from MRJob.protocol import JSONProtocol

class JSONProtocolExample(MRJob):

    INPUT_PROTOCOL = JSONProtocol
    OUTPUT_PROTOCOL = JSONProtocol
    INTERNAL_PROTOCOL = JSONProtocol

    def mapper(self, key, value):
        assert key == 1001
        assert value == {"value":10}
        assert isinstance(value, dict)
        yield key, value
    def reducer(self, key, values):
        for value in values:
            assert key == 1001
            assert value == {"value":10}
            yield key, value

if __name__ == '__main__':
    JSONProtocolExample.run()
        </pre>
  </div>
    <div id="tabs1-jvp">
        <pre>
# json_value_protocol.py

from MRJob.job import MRJob
from MRJob.protocol import JSONValueProtocol

class JSONValueProtocolExample(MRJob):

    INPUT_PROTOCOL = JSONValueProtocol
    OUTPUT_PROTOCOL = JSONValueProtocol
    INTERNAL_PROTOCOL = JSONValueProtocol

    def mapper(self, key, value):
        pass
    def reducer(self, key, values):
        pass

if __name__ == '__main__':
    JSONValueProtocolExample.run()
        </pre>
  </div>
</div>

####III.B.2 Importance of RawProtocols in Context of Total Sorting

While working with MRJob, it is critical to understand that MRJob is an abstraction layer above Hadoop Streaming, and does not extend or modify Hadoop Streaming's normal behaviors. Hadoop streaming works through Unix piping, and uses stdout and stderr as communication channels. In Unix environments, the protocol of data transfer is text streams – mappers, reducers and Hadoop libraries exchange data through text.

Therefore, data structures common in programming languages (e.g. dictionary in Python) have to be serialized to text for Hadoop Streaming to understand and consume. The implication of this on total sorting is this: since Hadoop only sees text, custom data structures (as keys) will be sorted as strings in their serialized forms.

MRJob abstracts away the data serialization and deserialization processes by the concept of protocols. For instance, JSONProtocol is capable of serializing complex data structures, and is used by default as INTERNAL_PROTOCOL. This frees the user from messy string manipulation and processing, but it does come with a cost.

One example is Python tuples:

```
# mapper
yield 1, ("value", "as", "tuple", True)

```

Inside the reducer however, when values are deserialized into Python objects, the (key, value) pair above will become:

```
1, ["value", "as", "tuple", True]

```

Namely, tuples become lists, due to the fact JSON does not support tuples.
Another example related to sorting: suppose mappers emit custom data structures as composite keys, such as:

```
# mapper
yield [12, 1, 2016]
```

Assuming we want to use Hadoop streaming's KeyFieldBasedComparator, when Hadoop streaming sees the JSON string [2016, 12, 1], it is very difficult to instruct Hadoop to understand the key consists of three fields (month, day, year), and sort them accordingly (e.g. we want sort by year, month, day).

Notice if we yield a date as [2016, 12, 01] instead, the string comparison coincidentally produces the same sort order as the underlying data (dates). However, to achieve this requires intimate knowledge of JSON (or other transportation formats); therefore, we recommend using RawProtocol or RawValueProtocol when advanced sorting is desired, which offers maximum flexibility to control key sorting and key hashing performed by Hadoop Streaming.

###III.C. Partitioning in MRJob

__Key points:__
* Challenge: which partition contains the largest/smallest values seems arbitrary.
* Hadoop streaming `KeyFieldBasedPartitioner` does not sort partition keys, even though it seemingly accepts Unix `sort` compatible configurations.
* MRJob uses Hadoop Streaming under the hood, therefore inherits the same problem

__Solution:__
* Understand the inner working of `HashPartitioner` and `KeyFieldBasedPartitioner`.
* Relationship between `KeyFieldBasedPartitioner` and `HashPartitioner`: * `KeyFieldBasedPartitioner` applies `HashPartitioner` on configured key field(s).
* Create the inverse function of `HashPartitioner` and assign partition keys accordingly.

####Understanding HashPartitioner

By default, Hadoop uses a library class HashPartitioner to compute the partition index for keys produced by mappers. It has a method called getPartition, which takes key.hashCode() & Integer.MAX_VALUE and finds the modulus using the number of reduce tasks. For example, if there are 10 reduce tasks, getPartition will return values 0 through 9 for all keys.

```
// HashPartitioner

partitionIndex = (key.hashCode() &amp; Integer.MAX_VALUE) % numReducers
```

In the land of native Hadoop applications (written in Java or JVM languages), keys can be any object type that is hashable (i.e. implements hashable interface). For Hadoop Streaming, however, keys are always string values. Therefore the hashCode function for strings is used:

<div style="margin:20px 0;background: #f8f8f8; overflow:auto;width:auto;border:0;border-width:.0;padding:.0;"><pre style="margin: 0; line-height: 125%">public <span style="color: #008000">int</span> hashCode() {
    <span style="color: #008000">int</span> h <span style="color: #666666">=</span> <span style="color: #008000">hash</span>;
    <span style="color: #008000; font-weight: bold">if</span> (h <span style="color: #666666">==</span> <span style="color: #666666">0</span> <span style="color: #666666">&amp;&amp;</span> value<span style="color: #666666">.</span>length <span style="color: #666666">&gt;</span> <span style="color: #666666">0</span>) {
        char val[] <span style="color: #666666">=</span> value;

        <span style="color: #008000; font-weight: bold">for</span> (<span style="color: #008000">int</span> i <span style="color: #666666">=</span> <span style="color: #666666">0</span>; i <span style="color: #666666">&lt;</span> value<span style="color: #666666">.</span>length; i<span style="color: #666666">++</span>) {
            h <span style="color: #666666">=</span> <span style="color: #666666">31</span> <span style="color: #666666">*</span> h <span style="color: #666666">+</span> val[i];
        }
        <span style="color: #008000">hash</span> <span style="color: #666666">=</span> h;
    }
    <span style="color: #008000; font-weight: bold">return</span> h;
}
</pre>
</div>

When we configure Hadoop Streaming to use KeyBasedPartitioner, the process is very similar. Hadoop Streaming will parse command line options such as -k2,2 into key specs, and extract the part of the composite key (in this example, field 2 of many fields) and read in the partition key as a string. For example, with the following configuration:

~~~~
"stream.map.output.field.separator" : ".",
"mapreduce.partition.keycomparator.options": "-k2,2",
Hadoop will extract a from a composite key 2.a.4 to use as the partition key.
~~~~

The partition key is then hashed (as a string) by the same hashCode function, its modulus using a number of reduce tasks yields the partition index.

(See KeyBasedPartitioner source code for the actual implementations.)

####Inverse HashCode Function

In order to preserve partition key ordering, we will construct an "inverse hashCode function", which takes as input the desired partition index and total number of partitions, and returns a partition key. This key, when supplied to the Hadoop framework (KeyBasedPartitioner), will hash to the correct partition index.

First, let's implement the core of `HashPartitioner` in Python:

```
#input:
def makeKeyHash(key, num_reducers):
    byteof = lambda char: int(format(ord(char), 'b'), 2)
    current_hash = 0
    for c in key:
        current_hash = (current_hash * 31 + byteof(c))
    return current_hash % num_reducers

# partition indices for keys: A,B,C; with 3 partitions
[makeKeyHash(x, 3) for x in "ABC"]

#output:
[2, 0, 1]
```


A simple strategy to implement an inverse hashCode function is to use a lookup table. For example, assuming we have 3 reducers, we can compute the partition index with makeKeyHash for keys "A", "B", and "C". The results are listed the the table below.

| <strong>Partition Key</strong> | <strong>Partition Index</strong> |
|-------------------|---------------------|
| A                 | 2                   |
| B                 | 0                   |
| C                 | 1                   |

In the mapper stage, if we want to assign a record to partition 0, for example, we can simply look at the partition key that generated the partition index 0, which in this case is "B".

####Total Order Sort with ordered partitions - illustrated

![Total Order Sort, Step 1](images/TOS1.png)

![Total Order Sort, Step 2](images/TOS2.png)

![Total Order Sort, Step 3](images/TOS3.png)

 
###III.D. MRJob Implementations

####III.D.1 MRJob implementation - single reducer - local mode


```Python
%%writefile singleReducerSortLocal.py

import sys
import MRJob
from MRJob.job import MRJob
from MRJob.step import MRStep

class singleReducerSortLocal(MRJob):
        
    def mapper(self, _, line):
        line = line.strip()
        key,value = line.split('\t')
        yield (sys.maxint - int(key)), value

    def reducer(self, key, value):
        for v in value:
            yield sys.maxint - key, v
        
    def steps(self):
        return [MRStep(
                    mapper=self.mapper,
                    reducer=self.reducer)
                ]

if __name__ == '__main__':
    singleReducerSortLocal.run()
``` 
    Overwriting singleReducerSortLocal.py



```Python
!Python singleReducerSortLocal.py generate_numbers.output > MRJob_singleReducer_local_sorted_output.txt
```


```Python
print "="*100
print "Single Reducer Local Sorted Output - MRJob"
print "="*100
!cat MRJob_singleReducer_local_sorted_output.txt
```

    ====================================================================================================
    Single Reducer Local Sorted Output - MRJob
    ====================================================================================================
    30  "do"
    28  "dataset"
    27  "creating"
    27  "driver"
    27  "experiements"
    26  "def"
    26  "descent"
    25  "compute"
    24  "code"
    24  "done"
    23  "descent"
    22  "corresponding"
    19  "consists"
    19  "evaluate"
    17  "drivers"
    15  "computational"
    15  "computing"
    15  "document"
    14  "center"
    13  "efficient"
    10  "clustering"
    9   "change"
    9   "during"
    7   "contour"
    5   "distributed"
    4   "develop"
    3   "different"
    2   "cluster"
    1   "cell"
    0   "current"

####III.D.2. MRJob implementation - single reducer - Hadoop mode


```Python
%%writefile singleReducerSort.py
#!~/anaconda2/bin/Python
# -*- coding: utf-8 -*-
import MRJob
from MRJob.job import MRJob
from MRJob.step import MRStep

class SingleReducerSort(MRJob):
    # By specifying sort values True, MRJob will do a secondary sort on the value, in this case the words.
    # ties will be broken by sorting words alphabetically in ascending order
    MRJob.SORT_VALUES = True   
    
    def mapper(self, _, line):
        line = line.strip()
        key,value = line.split('\t')
        yield int(key),value

    def reducer(self, key, value):
        for v in value:
            yield key, v
        
    def steps(self):
        JOBCONF_STEP = {
            'mapreduce.job.output.key.comparator.class': 'org.apache.Hadoop.mapred.lib.KeyFieldBasedComparator',
            'stream.map.output.field.separator':'\t',    
            'mapreduce.partition.keycomparator.options': '-k1,1nr -k2',
            'mapreduce.job.reduces': '1'
        }
        return [MRStep(jobconf=JOBCONF_STEP,
                    mapper=self.mapper,
                    reducer=self.reducer)
                ]

if __name__ == '__main__':
    SingleReducerSort.run()
```

    Overwriting singleReducerSort.py



```Python
!Python singleReducerSort.py -r Hadoop generate_numbers.output > MRJob_singleReducer_sorted_output.txt
```


```Python
print "="*100
print "Single Reducer Sorted Output - MRJob"
print "="*100
!cat MRJob_singleReducer_sorted_output.txt
```

    ====================================================================================================
    Single Reducer Sorted Output - MRJob
    ====================================================================================================
    30  "do"
    28  "dataset"
    27  "creating"
    27  "driver"
    27  "experiements"
    26  "def"
    26  "descent"
    25  "compute"
    24  "code"
    24  "done"
    23  "descent"
    22  "corresponding"
    19  "consists"
    19  "evaluate"
    17  "drivers"
    15  "computational"
    15  "computing"
    15  "document"
    14  "center"
    13  "efficient"
    10  "clustering"
    9   "change"
    9   "during"
    7   "contour"
    5   "distributed"
    4   "develop"
    3   "different"
    2   "cluster"
    1   "cell"
    0   "current"


####III.D.3. MRJob Multiple Reducers - With Un-Ordered Partiton

The equivalent of the Hadoop Streaming Total Order Sort implementation above


```Python
%%writefile MRJob_unorderedTotalOrderSort.py

import MRJob
from MRJob.job import MRJob
from MRJob.step import MRStep
from MRJob.protocol import RawValueProtocol
from MRJob.protocol import RawProtocol
from operator import itemgetter
import numpy as np

class MRJob_unorderedTotalOrderSort(MRJob):
    
    # Allows values to be treated as keys, so they can be used for sorting:
    MRJob.SORT_VALUES = True 
    
    # The protocols are critical. It will not work without these:
    INTERNAL_PROTOCOL = RawProtocol
    OUTPUT_PROTOCOL = RawProtocol
 
    def __init__(self, *args, **kwargs):
        super(MRJob_unorderedTotalOrderSort, self).__init__(*args, **kwargs)
        self.NUM_REDUCERS = 3
    
    
    def mapper(self, _, line):
        line = line.strip()
        key,value = line.split('\t')
        if int(key) > 20:
            yield "A",key+"\t"+value
        elif int(key) > 10:
            yield "B",key+"\t"+value
        else:
            yield "C",key+"\t"+value
            
    def reducer(self,key,value):
        for v in value:
            yield key, v

    
    def steps(self):
        
        JOBCONF_STEP1 = {
            'stream.num.map.output.key.fields':3,
            'stream.map.output.field.separator':"\t",
            'mapreduce.partition.keypartitioner.options':'-k1,1',
            'mapreduce.job.output.key.comparator.class': 'org.apache.Hadoop.mapred.lib.KeyFieldBasedComparator',
            'mapreduce.partition.keycomparator.options':'-k1,1 -k2,2nr -k3,3',
            'mapred.reduce.tasks': self.NUM_REDUCERS,
            'partitioner':'org.apache.Hadoop.mapred.lib.KeyFieldBasedPartitioner'
        }
        return [MRStep(jobconf=JOBCONF_STEP1,
                    mapper=self.mapper,
                    reducer=self.reducer)
                ]

if __name__ == '__main__':
    MRJob_unorderedTotalOrderSort.run()
```

    Overwriting MRJob_unorderedTotalOrderSort.py



```Python
!hdfs dfs -rmr /user/koza/sort/un_output 
!Python MRJob_unorderedTotalOrderSort.py  -r Hadoop generate_numbers.output \
    --output-dir=/user/koza/sort/un_output 
```


```Python
!hdfs dfs -ls /user/koza/sort/un_output 
```

    Found 4 items
    -rw-r--r--   1 koza supergroup          0 2016-08-20 19:28 /user/koza/sort/un_output/_SUCCESS
    -rw-r--r--   1 koza supergroup        116 2016-08-20 19:28 /user/koza/sort/un_output/part-00000
    -rw-r--r--   1 koza supergroup        125 2016-08-20 19:28 /user/koza/sort/un_output/part-00001
    -rw-r--r--   1 koza supergroup        152 2016-08-20 19:28 /user/koza/sort/un_output/part-00002

####III.D.4. MRJob Multiple Reducers - With Ordered Partitons

The final Total Order Sort with ordered partitions

####What's New

The solution we will delve into is very similar to the one discussed earlier in the Hadoop Streaming section. The only addition is Step 1B, where we take a desired partition index and craft a custom partition key such that Hadoop's KeyFieldBasedPartitioner hashes it back to the correct index.
 
Figure 6. Total order sort with custom partitioning.

```Python
%%writefile MRJob_multipleReducerTotalOrderSort.py
#!~/anaconda2/bin/Python
# -*- coding: utf-8 -*-

import MRJob
from MRJob.job import MRJob
from MRJob.step import MRStep
from MRJob.protocol import RawValueProtocol
from MRJob.protocol import RawProtocol
from operator import itemgetter
import numpy as np

class MRJob_multipleReducerTotalOrderSort(MRJob):
    
    # Allows values to be treated as keys
    MRJob.SORT_VALUES = True 
    
    # The protocols are critical. It will not work without these:
    INTERNAL_PROTOCOL = RawProtocol
    OUTPUT_PROTOCOL = RawProtocol
 
    def __init__(self, *args, **kwargs):
        super(MRJob_multipleReducerTotalOrderSort, self).__init__(*args, **kwargs)
        self.N = 30
        self.NUM_REDUCERS = 3
    
    def mapper_partitioner_init(self):
        
        def makeKeyHash(key, num_reducers):
            byteof = lambda char: int(format(ord(char), 'b'), 2)
            current_hash = 0
            for c in key:
                current_hash = (current_hash * 31 + byteof(c))
            return current_hash % num_reducers
        
        # printable ascii characters, starting with 'A'
        keys = [str(unichr(i)) for i in range(65,65+self.NUM_REDUCERS)]
        partitions = []
        
        for key in keys:
            partitions.append([key, makeKeyHash(key, self.NUM_REDUCERS)])

        parts = sorted(partitions,key=itemgetter(1))
        self.partition_keys = list(np.array(parts)[:,0])
        
        self.partition_file = np.arange(0,self.N,self.N/(self.NUM_REDUCERS))[::-1]
        
    def mapper_partition(self, _, line):
        line = line.strip()
        key,value = line.split('\t')
        
        # Prepend the approriate key by finding the bucket, and using the index to fetch the key.
        for idx in xrange(self.NUM_REDUCERS):
            if float(key) > self.partition_file[idx]:
                yield str(self.partition_keys[idx]),key+"\t"+value
                break
        
        
            
    def reducer(self,key,value):
        for v in value:
            yield key,v
            # To omit the partition key, specify 'None'
            # We are keeping it for illutration purposes
    
    def steps(self):
        
        JOBCONF_STEP1 = {
            'stream.num.map.output.key.fields':3,
            'mapreduce.job.output.key.comparator.class': 'org.apache.Hadoop.mapred.lib.KeyFieldBasedComparator',
            'stream.map.output.field.separator':"\t",
            'mapreduce.partition.keypartitioner.options':'-k1,1',
            'mapreduce.partition.keycomparator.options':'-k2,2nr -k3,3',
            'mapred.reduce.tasks': self.NUM_REDUCERS,
            'partitioner':'org.apache.Hadoop.mapred.lib.KeyFieldBasedPartitioner'
        }
        return [MRStep(jobconf=JOBCONF_STEP1,
                    mapper_init=self.mapper_partitioner_init,
                    mapper=self.mapper_partition,
                    reducer=self.reducer)
                ]


if __name__ == '__main__':
    MRJob_multipleReducerTotalOrderSort.run()
```

Overwriting MRJob_multipleReducerTotalOrderSort.py



```Python
!hdfs dfs -rm -r /user/koza/total_order_sort
!Python MRJob_multipleReducerTotalOrderSort.py -r Hadoop generate_numbers.output \
    --output-dir='/user/koza/total_order_sort' 
```


```Python
print "="*100
print "Total Order Sort with multiple reducers - notice that the part files are also in order."
print "="*100
print "/part-00000"
print "-"*100
!hdfs dfs -cat /user/koza/total_order_sort/part-00000
print "-"*100
print "/part-00001"
print "-"*100
!hdfs dfs -cat /user/koza/total_order_sort/part-00001
print "-"*100
print "/part-00002"
print "-"*100
!hdfs dfs -cat /user/koza/total_order_sort/part-00002

```

    ====================================================================================================
    Total Order Sort with multiple reducers - notice that the part files are also in order.
    ====================================================================================================
    /part-00000
    ----------------------------------------------------------------------------------------------------
    B   30  do  
    B   28  dataset 
    B   27  creating    
    B   27  driver  
    B   27  experiements    
    B   26  def 
    B   26  descent 
    B   25  compute 
    B   24  code    
    B   24  done    
    B   23  descent 
    B   22  corresponding   
    ----------------------------------------------------------------------------------------------------
    /part-00001
    ----------------------------------------------------------------------------------------------------
    C   19  consists    
    C   19  evaluate    
    C   17  drivers 
    C   15  computational   
    C   15  computing   
    C   15  document    
    C   14  center  
    C   13  efficient   
    ----------------------------------------------------------------------------------------------------
    /part-00002
    ----------------------------------------------------------------------------------------------------
    A   10  clustering  
    A   9   change  
    A   9   during  
    A   7   contour 
    A   5   distributed 
    A   4   develop 
    A   3   different   
    A   2   cluster 
    A   1   cell    


We now have exactly what we were looking for: Total Order Sort, with the added benefit of ordered partitions. Notice that the top results are stored in part-00000, the next set of results is stored in part-00001, etc., because we hashed the keys (A,B,C) to those file names.

##Section IV - Sampling Key Spaces

Keypoints:
* Random Sampling - Easy implementation when we know the total size of the data.
* Reservoir Sampling - A method to sample the data with equal probability for all data points when the size of the data is unknown. The algorithm works as follows:
~~~~
    n = desired sample size
    reservoir = []
    for d in data
        if reservoir size < n
            add d to reservoir
        else:
            choose random location in reservoir
            flip coin whether to replace the existing d with new d
~~~~
    
(This paper has a nice explanation of reservoir sampling, see: 2.2 Density-Biased Reservoir Sampling [http://science.sut.ac.th/mathematics/pairote/uploadfiles/weightedkm-temp2_EB.pdf](http://science.sut.ac.th/mathematics/pairote/uploadfiles/weightedkm-temp2_EB.pdf).)

Consider the following example in which we assumed a uniform distribution of the data. For simplicity, we made our partition file based on that assumption. In reality this is rarely the case, and we should make our partition file based on the actual distribution of the data to avoid bottlenecks. A bottleneck would occur if the majority of our data resided in a single bucket, as could happen with a typical power law distribution.

Consider the following example:


```Python
# Visualizae Partition File
%matplotlib inline
import pylab as pl
fig, ax = pl.subplots(figsize=(10,6))

ax.hist(sampleData,color="#48afe0",edgecolor='none')

xcoords = [.15,.3,.45]
for xc in xcoords:
    pl.axvline(x=xc,color="#197f74", linewidth=1)

ax.spines['top'].set_visible(False)
ax.spines['right'].set_visible(False)
ax.spines['bottom'].set_visible(False)
ax.spines['left'].set_visible(False)


pl.title("4 uniformly spaced buckets")
pl.show()
```

![Total Order Sort, Step 3](images/buckets.png)

_Figure 7. Uniform partitions over skewed data._

If we made a uniform partition file the (above example has 4 buckets), we would end up with most of the data in a single reducer, and this would create a bottle neck. To fix this, we must first generate a sample of our data; then, based on this sample, create a partition file that distributes the keys more evenly.

###IV.A. Random Sample implementation

```Python
%%writefile MRJob_RandomSample.py
#!~/anaconda2/bin/Python
# -*- coding: utf-8 -*-

#########################################################
#  Emit a random sample of 1/1000th of the data
#########################################################


import numpy as np
import MRJob
from MRJob.job import MRJob
from MRJob.step import MRStep

class MRbuildSample(MRJob):
    
    def steps(self):
        return [MRStep(mapper=self.mapper)]
    
    def mapper(self,_,line):
        s = np.random.uniform(0,1)
        if s < .001: 
            yield None, line

    
if __name__ == '__main__':
    MRJob_RandomSample.run()
```

Overwriting MRJob_RandomSample.py

###IV.B. Custom partition file implementation

####Percentile Based Partitioning

Once we have a (small) sampled subset of data, we can compute partition boundaries by examining the distribution of this subset, and find appropriate percentiles based on the number of desired partitions. A basic implementation using NumPy is provided below:


```Python
def readSampleData():
    # A sample of the data is stored in a single file in sampleData/part-00000
    # from the previous step (MRJob_RandomSample.py)
    sampleData = []  

    with open("sampleData/part-00000","r") as f:
        lines = f.readlines()
        for line in lines:
            line = line.strip()
            avg,lisst = line.split("\t")
            sampleData.append(float(avg))
            
    return sampleData
```


```Python
from numpy import array, percentile, linspace, random

def partition(data, num_of_partitions=10, return_percentiles=False):
    # remove percentile 100
    qs = linspace(0, 100, num=num_of_partitions, endpoint=False)
    if not return_percentiles:
        return percentile(data, qs)
    return percentile(data, qs), qs

sampleData = readSampleData()

# (partitionFile, percentiles)
partition(sampleData, 4, return_percentiles=True)
```
~~~~
#Output:
(array([ 0.00986008,  0.04131517,  0.06350346,  0.09810477]),
 array([  0.,  25.,  50.,  75.]))
~~~~

####Visualize Partition
 
![Total Order Sort, Step 3](images/buckets-apart.png)
    Sample Data min 0.00986007565667
    Sample Data max 0.514811335171
    [0.009860075656665268, 0.04131516964443623, 0.06350346288221721, 0.0981047679070244]

_Figure 8. Percentile based partitions over skewed data._

##Section V - Spark implementation

For this section, you will need to install Spark. We are using a local installation, version 1.6


```
# Start Spark
import os
import sys
spark_home = os.environ['SPARK_HOME'] = \
   '/usr/local/share/spark-1.6.0-bin-Hadoop2.6'

if not spark_home:
    raise ValueError('SPARK_HOME enviroment variable is not set')
sys.path.insert(0,os.path.join(spark_home,'Python'))
sys.path.insert(0,os.path.join(spark_home,'Python/lib/py4j-0.8.2.1-src.zip'))
execfile(os.path.join(spark_home,'Python/pyspark/shell.py'))
app_name = "total-sort"
    
master = "local[*]"
conf = pyspark.SparkConf().setAppName(app_name).setMaster(master)
# print sc
# print sqlContext

```

    Welcome to
          ____              __
         / __/__  ___ _____/ /__
        _\ \/ _ \/ _ `/ __/  '_/
       /__ / .__/\_,_/_/ /_/\_\   version 1.6.0
          /_/
    
    Using Python version 2.7.11 (default, Dec  6 2015 18:57:58)
    SparkContext available as sc, HiveContext available as sqlContext.

###SPARK - Key features

From the Spark website:
* Spark is a fast and general engine for large-scale data processing.
* Spark runs on Hadoop, Mesos, standalone, or in the cloud.
* Run programs up to 100x faster than Hadoop MapReduce in memory, or 10x faster on disk.
* Write applications quickly in Java, Scala, Python, R.
* Spark offers over 80 high-level operators that make it easy to build parallel apps. And you can use it interactively from the Scala, Python and R shells.
* Apache Spark has an advanced Directed Acyclic Graph (DAG) execution engine that supports cyclic data flow and in-memory computing.

![Total Order Sort, Step 3](images/spark.png)
 
_Figure 9. Spark data flow._

At the core of Spark are "Resilient Distributed Datasets (RDDs), a distributed memory abstraction that lets programmers perform in-memory computations on large clusters in a fault-tolerant manner" (https://www2.eecs.berkeley.edu/Pubs/TechRpts/2011/EECS-2011-82.pdf).

An RDD is simply a distributed collection of elements (Key-Value records).

* In Spark all work is expressed as either creating new RDDs, running (lazy) transformations on existing RDDs, or performing actions on RDDs to compute a result.
* Under the hood, Spark automatically distributes the data contained in RDDs across your cluster and parallelizes the operations you perform on them.

###Total Sort in pyspark (Spark Python API)

As before, to achieve Total Order Sort, we must first partition the data such that it ends up in appropriately ordered buckets (partitions, filenames), and then sort within each partition. There are a couple of ways to do this in Spark, but they are not all created equal.

####repartition &amp; sortByKey VS repartitionAndSortWithinPartitions

[https://spark.apache.org/docs/1.6.2/programming-guide.html#working-with-key-value-pairs](https://spark.apache.org/docs/1.6.2/programming-guide.html#working-with-key-value-pairs)

__repartition:__ 
Reshuffle the data in the RDD randomly to create either more or fewer partitions and balance it across them. This always shuffles all data over the network.

__sortByKey:__ 
When called on a dataset of (K, V) pairs where K implements Ordered, returns a dataset of (K, V) pairs sorted by keys in ascending or descending order, as specified in the boolean ascending argument.

The sortByKey function reshuffles all the data a second time!

__repartitionAndSortWithinPartitions:__
 Repartition the RDD according to the given partitioner and, within each resulting partition, sort records by their keys. This is more efficient than calling repartition and then sorting within each partition because it can push the sorting down into the shuffle machinery.


```Python
from operator import itemgetter
import numpy as np

text_file = sc.textFile('generate_numbers.output')
NUM_REDUCERS = 3

# parse input #
def readData(line):
    x = line.split("\t")
    return [int(x[0]),x[1]],""

# Partition function #        
def top_down(x):
    if x[0] > 20:
        return 0
    elif x[0] > 10:
        return 1
    else:
        return 2
    

rdd = text_file.map(readData)


'''
repartitionAndSortWithinPartitions(numPartitions=None, partitionFunc=<function portable_hash at 0x7f2bec385230>, 
ascending=True, keyfunc=<function <lambda> at 0x7f2bec3839b0>) Repartition the RDD according to the given 
partitioner and, within each resulting partition, sort records by their keys.

By using this function we avoid unnecessary shuffling. In contrast, the sortByKey function reshuffles all the data 
and is not efficient.
'''
top = rdd.repartitionAndSortWithinPartitions(numPartitions=NUM_REDUCERS,
                                                    ascending=True, 
                                                    partitionFunc=top_down,
                                                    keyfunc=lambda x: (-x[0],x[1]))
```

By using glom we can see each partition in its own array. We also have a secondary sort on the "word" (in fact this is the latter part of a complex key) in ascending order.


```Python
print top.getNumPartitions(), "Partitions"
for i,d in enumerate(top.glom().collect()):
    print "="*50
    print "partition ",i
    print "="*50
    for j in d:
        print j[0][0],"\t",j[0][1]
```

    3 Partitions
    ==================================================
    partition  0
    ==================================================
    30  do
    28  dataset
    27  creating
    27  driver
    27  experiements
    26  def
    26  descent
    25  compute
    24  code
    24  done
    23  descent
    22  corresponding
    ==================================================
    partition  1
    ==================================================
    19  consists
    19  evaluate
    17  drivers
    15  computational
    15  computing
    15  document
    14  center
    13  efficient
    ==================================================
    partition  2
    ==================================================
    10  clustering
    9   change
    9   during
    7   contour
    5   distributed
    4   develop
    3   different
    2   cluster
    1   cell
    0   current

##Final Remarks
A note on <code>TotalSortPartitioner</code>: Hadoop has built in TotalSortPartitioner, which uses a partition file _partition.lst to store a pre-built order list of split points.TotalSortPartitioner uses binary search / Trie to look up the ranges a given record falls into.

##References
1. [http://wiki.apache.org/Hadoop/](http://wiki.apache.org/Hadoop/)
2. [http://Hadoop.apache.org/docs/stable1/streaming.html#Hadoop+Streaming](http://Hadoop.apache.org/docs/stable1/streaming.html#Hadoop+Streaming)
3. [http://MRJob.readthedocs.io/en/latest/index.html](http://MRJob.readthedocs.io/en/latest/index.html)
4. [http://www.theUnixschool.com/2012/08/linux-sort-command-examples.html](http://www.theUnixschool.com/2012/08/linux-sort-command-examples.html)
5. [https://Hadoop.apache.org/docs/r2.7.2/Hadoop-streaming/HadoopStreaming.html](https://Hadoop.apache.org/docs/r2.7.2/Hadoop-streaming/HadoopStreaming.html)
6. [http://Hadoop.apache.org/](http://Hadoop.apache.org/)
7. [https://Hadoop.apache.org/docs/r2.7.2/Hadoop-project-dist/Hadoop-common/SingleCluster.html](https://Hadoop.apache.org/docs/r2.7.2/Hadoop-project-dist/Hadoop-common/SingleCluster.html)
8. [https://Hadoop.apache.org/docs/r1.2.1/streaming.html#Hadoop+Comparator+Class](https://Hadoop.apache.org/docs/r1.2.1/streaming.html#Hadoop+Comparator+Class)
9. [https://github.com/Yelp/MRJob](https://github.com/Yelp/MRJob)
10. [https://Pythonhosted.org/MRJob/guides/configs-Hadoopy-runners.html](https://Pythonhosted.org/MRJob/guides/configs-Hadoopy-runners.html)
11. [http://docs.aws.amazon.com/ElasticMapReduce/latest/DeveloperGuide/emr-steps.html](http://docs.aws.amazon.com/ElasticMapReduce/latest/DeveloperGuide/emr-steps.html)
12. [https://github.com/apache/Hadoop/blob/2e1d0ff4e901b8313c8d71869735b94ed8bc40a0/Hadoop-mapreduce-project/Hadoop-mapreduce-client/Hadoop-mapreduce-client-core/src/main/java/org/apache/Hadoop/mapreduce/lib/partition/KeyFieldBasedPartitioner.java](https://github.com/apache/Hadoop/blob/2e1d0ff4e901b8313c8d71869735b94ed8bc40a0/Hadoop-mapreduce-project/Hadoop-mapreduce-client/Hadoop-mapreduce-client-core/src/main/java/org/apache/Hadoop/mapreduce/lib/partition/KeyFieldBasedPartitioner.java)
13. [http://science.sut.ac.th/mathematics/pairote/uploadfiles/weightedkm-temp2_EB.pdf](http://science.sut.ac.th/mathematics/pairote/uploadfiles/weightedkm-temp2_EB.pdf)
14. [http://spark.apache.org/](http://spark.apache.org/)
15. [https://www2.eecs.berkeley.edu/Pubs/TechRpts/2011/EECS-2011-82.pdf](https://www2.eecs.berkeley.edu/Pubs/TechRpts/2011/EECS-2011-82.pdf)
16. [https://spark.apache.org/docs/1.6.2/programming-guide.html#working-with-key-value-pairs](https://spark.apache.org/docs/1.6.2/programming-guide.html#working-with-key-value-pairs)
17. [https://github.com/facebookarchive/Hadoop-20/blob/master/src/mapred/org/apache/Hadoop/mapred/lib/TotalOrderPartitioner.java](https://github.com/facebookarchive/Hadoop-20/blob/master/src/mapred/org/apache/Hadoop/mapred/lib/TotalOrderPartitioner.java)
