---
title: 'The Pythonic Poet'
summary:
    enabled: '1'
    size: 1
    format: short
jscomments:
    provider: facebook
taxonomy:
    tag:
        - HCI
    category:
        - blog
author:
    name: 'Emily Paul and Andrea Gagliano'
    org: 'University of California, Berkeley'
header_image: user/themes/isrtheme/images/sonnet.jpg
---

<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>

<script src="http://a11y.nicolas-hoffmann.net/modal/js/jquery-accessible-modal-window-aria.js"></script>

<div id="endorsement" class="hidden modal">

<p>This paper describes an algorithm for automated generation of Shakespearian-style sonnets.  This work presents a novel approach for creating a high-precision rhyme bank, using a combination of a phonological dictionary and a unique algorithm for finding rhyming word pairs from a poetry collection.  I think this will be an interesting contribution to the literature on literary text generation.</p>

</div>

<button class="js-modal" data-modal-prefix-class="simple-animated" data-modal-content-id="endorsement" data-modal-title="Faculty Endorsement" data-modal-close-text="Close" data-modal-close-title="Close this modal window">Show faculty endorsement</button>

##Overview

Natural language processing often aims to disambiguate text. But, not all text thrives on clarity. For example, poetry often relies on the ambiguity of words to create figurative relationships with rich imagery. The work here is preliminary and exploratory in nature and seeks to contribute to the usage of natural language processing tools in more creative tasks, such as poetry. We start with a constrained problem - sonnet generation.

This paper describes the automatic creation of four line sonnet stanzas. Initially, a word is randomly selected from a rhyme bank to end the first line. Then, the first line is generated backwards using a trigram language model trained on existing sonnets. The process is repeated for subsequent lines using end words that fulfill the target rhyme scheme (a-b-a-b for Shakespearean or a-b-b-a for Petrarchan). In creating the rhyme bank, two approaches were considered: one to infer rhymes given a partial tagging of rhymes based on phonemes <a href="#fn1" id="ref1">[1]</a> and the other using an expectation maximization learning algorithm to extract rhyme relationships irrespective of phonemes. 

This work relies on many of the insights and methodologies from previous research on computational poetry analysis and computational poetry generation. 

Work on computational poetry analysis includes McCurdy et al.’s identification of rhyme types in poetry <a href="#fn2" id="ref2">[2]</a>,  which uses phonemes supplemented with automated techniques including letter to sound rules as proposed by Black et al. and syllable segmentation methods using Support Vector Machines and Hidden Markov Models as proposed by Bartlett et al. <a href="#fn3" id="ref3">[3]</a><a href="#fn4" id="ref4">[4]</a><a href="#fn5" id="ref5">[5]</a>     Reddy, S. and Knight, K. use an expectation maximization learning algorithm to extract rhymes from the ending words of sonnet lines without prior knowledge of word phonemes<a href="#fn6" id="ref6">[6]</a>.  Addanki, K. and Wu, D. extended Reddy and Knight’s work to hip hop lyrics using Hidden Markov Models to identify rhyming words in hip hop lines. <a href="#fn7" id="ref7">[7]</a>

In their computational poetry generation work, Tobing and Manurung <a href="#fn8" id="ref8">[8]</a> and Gervás <a href="#fn9" id="ref9">[9]</a> describe some of the most commonly used poetry generation approaches including template-based generation, stochastic language modeling, and evolutionary algorithms. 

##Sonnet Stanza Generation

###Data

Throughout this work, we use a corpus of sonnets written by Shakespeare <a href="#fn10" id="ref10">[10]</a> and other poets <a href="#fn11" id="ref11">[11]</a> to identify rhymes and train our language generation model. The corpus is 1.125 megabytes of data and includes 1,871 poems, which equates to 26,194 lines of poetry.

###Rhyme identification

To select rhyme pairs for the stanza, we investigated two approaches. The first approach uses Carnegie Mellon University’s pronunciation dictionary <a href="#fn12" id="ref12">[12]</a> (the Prondict) to detect rhymes in the corpus, based on phonemes, and infers additional rhymes given known rhyme schemes of sonnets (Inferring Rhymes). The second approach implements an unsupervised expectation maximization learning algorithm, as described by Sravana, R. and Knight, K., <a href="#fn13" id="ref13">[13]</a> to tag a poem with a particular rhyme scheme (EM Rhymes). In our poetry generation, we ultimately relied on the Inferring Rhymes approach.

Both approaches extend beyond simply looking up pronunciations in the Prondict, because (a) not all words are included in the Prondict, (b) the Prondict provides no understanding of words that poets typically rhyme together, and (c) the Prondict is limited in identifying non-true rhymes. <a href="#fn14" id="ref14">[14]</a>

###Approach 1: Inferring rhymes based on phonemes

In this approach, for each sonnet in the corpus, we begin by looking at the end words of each line. 

>**Example: end word extraction**<br />
>as one who pausing on the tedious slope<br />
>of some high mountain thoughtfully looks back<br />
>on the long painful and uncertain track<br />
>his feet have trodden then with awe and hope<br />
...<br />

>End words: [slope, back, track, hope, ...]

Using the Prondict, the last two phonemes of each end word are identified. If two words have the same last two phonemes, they are tagged as a rhyme pair. Over an entire sonnet, this results in an initial rhyme scheme. If an end word does not exist in the Prondict a rhyme is not identified and a ‘None’ is used as placeholder in the rhyme scheme. For example, a rhyme scheme may look like:

>**Example: initial rhyme scheme**

>[a, None, b, a, a, None, b, a, e, f, e, f, None, None]

Since the Shakespearean and Petrarchan rhyme schemes for sonnets are known, we can infer the complete rhyme scheme: 

>**Example: inferred rhyme scheme**

>Identified rhyme scheme using Prondict: [a, None, b, a, a, None, b, a, e, f, e, f, None, None]<br />
>Inferred rhyme scheme: [a, b, b, a, a, b, b, a, e, f, e, f, g, g]

For the sonnets written by Shakespeare, the rhyme scheme is set at [a, b, a, b, c, d, c, d, e, f, e, f, g, g], but in the other sonnets in our corpus the poets may take more liberty, which results in a variety of rhyme schemes. Following are examples of rhyme schemes that show this range of variation: [a, b, b, a, a, b, b, a, e, f, e, f, g, g] or [a, b, a, b, a, b, b, a, c, c, d, d, e, e].

As such, for the sonnets written by Shakespeare, regardless of how many end words are tagged as ‘None’, we are able to infer rhyme pairs. For the other sonnets, rhymes are inferred on a stanza-by-stanza basis by looking at lines 1-4, 5-8, and 9-14 in isolation. 

If a stanza has three or more end words that are unidentifiable using the Prondict, we elect not to infer rhymes from it. Similarly, in some cases in which a stanza has two unidentifiable end words, we are not able to confidently select the appropriate rhyme scheme, as seen in TABLE 1 (below). When only a single word in a four line stanza is unidentifiable, we are always able to confidently select a rhyme scheme. 


**TABLE 1**

| Partially identified rhyme scheme* | Feasible rhyme schemes       | Can we confidently infer rhymes? |
|------------------------------------|------------------------------|----------------------------------|
| [None, None, a, b]                 | [a, b, b, a] or [a, b, a, b] | No                               |
| [None, None, b, b]                 | [a, a, b, b]                 | Yes                              |
| [None, a, None, a]                 | [a, b, a, b]                 | Yes                              |
| [None, a, None, b]                 | [a, b, b, a] or [a, a, b, b] | No                               |
| [a, None, None, b]                 | [a, b, a, b] or [a, a, b, b] | No                               |
| [a, b, None, None]                 | [a, b, a, b] or [a, b, b, a] | No                               |
| [a, a, None, None]                 | [a, a, b, b]                 | Yes                              |
| [a, None, a, None]                 | [a, b, a, b]                 | Yes                              |
| [a, None, b, None]                 | [a, b, b, a] or [a, a, b, b] | No                               |
| [None, a, a, None]                 | [a, b, b, a]                 | Yes                              |
| [None, a, b, None]                 | [a, b, a, b] or [a, a, b, b] | No                               |


###Approach 2: Learning rhymes from unsupervised expectation maximization algorithm

Our second approach aims to extract rhymes based on the statistical closeness of words within stanzas without relying on the sounds of given words. We use an unsupervised expectation maximization learning algorithm to tag stanzas with their most likely rhyme scheme. This algorithm, developed by Sravana, R. and Knight, K., <a href="#fn15" id="ref15">[15]</a> is driven by the theory that if two end words co-occur in multiple n-line stanzas, then those two words are more likely to rhyme. 

>**Example: identifying word pairs with high rhyme strength**

>*4-line stanza*<br />
>as one who pausing on the tedious slope<br />
>of some high mountain thoughtfully looks back<br />
>on the long painful and uncertain track<br />
>his feet have trodden then with awe and hope<br />

>End words: [slope, back, track, hope]

>*4-line stanza*<br />
>out of the mistland of the past come back<br />
>and hold my hand in silence smile on me<br />
>just once to cheer me on the lonely track<br />
>that leads from sorrow to uncertainty<br />

>End words: [back, me, track, uncertainty]

In the above stanzas, the end words ‘back’ and ‘track’ would have a higher rhyme strength, because they co-occur in more than one stanza. 

###Selecting an approach

The Inferring Rhymes approach has perfect precision in identifying rhyme pairs because rhymes are only extracted if we are certain of the inferred rhyme scheme. Although, this means that this approach does not recall all of the rhyme pairs from the corpus. However, due to its probabilistic nature, with the EM Rhymes approach we cannot guarantee perfect precision, but we can achieve perfect recall. Thus, we elect to rely solely on the Inferring Rhymes approach because of its high level of precision as required in the rhyme bank creation. 

###Rhyme bank
We construct a rhyme bank by combining all of the rhyme pairs identified, based on their phonemes in the Prondict, into rhyme sets. To include words that are not in the Prondict vocabulary, we transitively add them to the rhyme sets. For example, if there is the phoneme sound (‘T’, ‘ER0’), it corresponds to the rhyme set [‘flatter’, ‘matter’, ‘character’]. With an inferred rhyme pair of (‘daughter’, ‘flatter’), we can transitively add ‘daughter’ to the rhyme set. 

Due to the transitivity, the cost of inaccurately inferring a rhyme is high. For example, if we incorrectly claim a rhyme, such as (‘day’, ‘flatter’), then when we create the rhyme dictionary, we will mistakenly create a rhyme set, such as [‘flatter’, ‘matter’, ‘character’, ‘daughter’, ‘day’]. Precision in rhyme pairs is vital, hence our selection of using the Inferring Rhymes approach.

The resulting rhyme bank has 344 rhyme sounds. Transitively adding words to the rhyme bank increases the size of the rhyme bank from 4,336 words to 8,265 words (a 90.6% increase). 

###Poem generation

###Building a line
We generate lines using a trigram language model trained on the sonnet corpora. <a href="#fn16" id="ref16">[16]</a> The lines are generated in reverse allowing us to initiate the line generation with a randomly selected word from the rhyme bank. We then build the lines backwards from the end word, stopping at ten syllables. <a href="#fn17" id="ref17">[17]</a>

After the randomly selected end word, the next word in the line is a weighted pick from all of the words that appear before that word in the corpus. After the last and penultimate words are selected, each subsequent word—moving backwards through the line—is selected by a weighted pick from words that precede the bigram in the corpus. The weights are determined by a Maximum Likelihood Estimate (MLE) that measures the count of the trigram in the corpus relative to the count of the bigram that follows it.

P(w_i  | w_(i-2),w_(i-1)) = count(w_(i-2),w_(i-1),w_i) / count(w_(i-2),w_(i-1))


###Implementing rhyme scheme

Currently we are able to generate poems in either a Shakespearean (a-b-a-b) or Petrarchan (a-b-b-a) rhyme scheme. In order to select our a and b rhymes for each poem, the poem generation function randomly selects two different lists of rhyming words from the rhyme bank. The end word for the first a line is randomly selected from the a rhyme list and used to generate the first line of the poem. The end word for the second line is randomly selected from the b rhyme list to generate the second line of the poem. For the third and fourth rhymes a word is selected that rhymes with the appropriate preceding line. 

<input id="stanza" type="button" value="See a Generated Stanza" onclick="return_random_stanza();" />

<p id="stanzap"></p>

##Future Work and Conclusion

We are exploring various methods for creating semantic cohesion and enhancing figurative language across our stanzas in hopes of expanding to full sonnet generation. We also hope to ensure consistent pronouns across lines and add appropriate punctuation to simulate a more human-like poem.

Our aim with this work is not to claim that computers can replace human poets but rather to use natural language processing as a resource for both exploring the creative potential of the machine and reflecting on what is involved in human creative work.

###Acknowledgements

Thank you to Kyle Booten, Marti Hearst and David Bamman for their continued guidance, insight, and direction. 


###<em>Can you tell the difference between real Shakespearean stanzas and computer-generated ones?</em>
<input id="turingpoems" type="button" value="Take a Quiz" onclick="populate_quiz();" />

<div id="quiz">
    <p><strong>For each stanza, choose Shakespeare or Python, then click "Show Answers" to see if you were right!</strong></p>
    <div class="pregunta"></div>
    <div class="pregunta"></div>
    <div class="pregunta"></div>
    <button id="showanswer">Show Answers</button>
</div>

<h2>End Notes</h2>
<p id="endnotes">
<sup id="fn1"><span>1. A phoneme is a distinct unit of sound. Words are composed of many phonemes.<a href="#ref1" title="Jump back to footnote 1 in the text.">↩</a></sup></span><br>
<sup id="fn2"><span>2. McCurdy, N., Lein, J., Coles, K., Meyer, M. 2015. Poemage: Visualizing the Sonic Topology of a Poem. IEEE Transactions on Visualization and Computer Graphics.<a href="#ref2" title="Jump back to footnote 2 in the text.">↩</a></sup></span><br>
<sup id="fn3"><span>3. McCurdy, N., Srikumar, V., Meyer, M. 2015. RhymeDesign: A Tool for Analyzing Sonic Devices in Poetry. Proceedings of NAACL-HLT Fourth Workshop on Computational Linguistics for Literature.<a href="#ref3" title="Jump back to footnote 3 in the text.">↩</a></sup></span><br>
<sup id="fn4"><span>4. Black, A., Lenzo, K., and Pagel, V. 1998. Issues in Building General Letter to Sound Rules.<a href="#ref4" title="Jump back to footnote 4 in the text.">↩</a></sup></span><br>
<sup id="fn5"><span>5. Bartlett, S., Kondrak, G., and Cherry, C. 2009. On the Syllabification of Phonemes. Human Language Technologies: The 2009 Annual Conference of the North American Chapter of the ACL.<a href="#ref5" title="Jump back to footnote 5 in the text.">↩</a></sup></span><br>
<sup id="fn6"><span>6. Sravana, R., Knight, K. 2011. Unsupervised Discovery of Rhyme Schemes. Proceedings of the 49th Annual Meeting of the Association for Computational Linguistics: shortpapers.<a href="#ref6" title="Jump back to footnote 6 in the text.">↩</a></sup></span><br>
<sup id="fn7"><span>7. Addanki, K. and Wu, D. 2013. Unsupervised Rhyme Scheme Identification in Hip Hop Lyrics Using Hidden Markov Models.<a href="#ref7" title="Jump back to footnote 7 in the text.">↩</a></sup></span><br>
<sup id="fn8"><span>8. Tobing, B. C. L. and Manurung, R. A Chart Generation System for Topical Metrical Poetry.<a href="#ref8" title="Jump back to footnote 8 in the text.">↩</a></sup></span><br>
<sup id="fn9"><span>9. Gervás, P. 2013. Computational Modelling of Poetry Generation. Proceedings of the AISB 13 Symposium on AI and Poetry.<a href="#ref9" title="Jump back to footnote 9 in the text.">↩</a></sup></span><br>
<sup id="fn10"><span>10. Provided by Kevin Knight from the Information Sciences Institute of University of Southern California.<a href="#ref10" title="Jump back to footnote 10 in the text.">↩</a></sup></span><br>
<sup id="fn11"><span>11. Sourced from sonnets.org.<a href="#ref11" title="Jump back to footnote 11 in the text.">↩</a></sup></span><br>
<sup id="fn12"><span>12. Carnegie Mellon University Pronunciation Dictionary. http://www.speech.cs.cmu.edu/cgi-bin/cmudict.<a href="#ref12" title="Jump back to footnote 12 in the text.">↩</a></sup></span><br>
<sup id="fn13"><span>13. Sravana, R., Knight, K. 2011. Unsupervised Discovery of Rhyme Schemes. Proceedings of the 49th Annual Meeting of the Association for Computational Linguistics: shortpapers.<a href="#ref13" title="Jump back to footnote 13 in the text.">↩</a></sup></span><br>
<sup id="fn14"><span>14. McCurdy, N., et. al. identified 24 different rhyme types (e.g. Identical rhyme, Semirhyme, Syllabic rhyme, etc.) and had to supplement the Prondict with support vector machines, letter to sound rules, and syllable segmentation methods for out of dictionary words. Poets have the liberty to use non-true rhyme types, so we wanted to capture these types of rhymes in our work in hopes of giving a more authentic feel to the poems. <a href="#ref14" title="Jump back to footnote 14 in the text.">↩</a></sup></span><br>
<sup id="fn15"><span>15. Sravana, R., Knight, K. 2011. Unsupervised Discovery of Rhyme Schemes. Proceedings of the 49th Annual Meeting of the Association for Computational Linguistics: shortpapers. <a href="#ref15" title="Jump back to footnote 15 in the text.">↩</a></sup></span><br>
<sup id="fn16"><span>16. The code for the language model was adapted from BigFav’s ngram language model code. Code available at https://github.com/BigFav/n-grams.<a href="#ref16" title="Jump back to footnote 16 in the text.">↩</a></sup></span><br>
<sup id="fn17"><span>17. Syllables are counted by looking up the words in the Prondict.<a href="#ref17" title="Jump back to footnote 17 in the text.">↩</a></sup></span><br>
</p>



##Works Cited

<ul>
<li id="addanki" class="ref">Addanki, K. and Wu, D. (2013). “Unsupervised rhyme scheme identification in hip hop lyrics using Hidden Markov Models.” Statistical language and speech processing: First international conference, SLSP 2013, Tarragona, Spain, July 29-31, 2013. Proceedings, 39-50.
<li id="bartlett" class="ref">Bartlett, S., Kondrak, G., and Cherry, C. (2009). “On the syllabification of phonemes.” Human Language Technologies: The 2009 Annual Conference of the North American Chapter of the ACL.
<li id="black" class="ref">Black, A., Lenzo, K., and Pagel, V. (1998). “Issues in building general letter to sound rules. ESCA Synthesis Workshop, Australia 1998.
<li id="gervas" class="ref">Gervás, P. (2013). “Computational modelling of poetry generation.” Proceedings of the AISB 13 Symposium on AI and Poetry.
<li id="mccurdy1" class="ref">McCurdy, N., Lein, J., Coles, K., Meyer, M. (2015). “Poemage: Visualizing the sonic topology of a poem.” IEEE Transactions on Visualization and Computer Graphics.
<li id="mccurdy2" class="ref">McCurdy, N., Srikumar, V., Meyer, M. (2015). “RhymeDesign: A tool for analyzing sonic devices in poetry.” Proceedings of NAACL-HLT Fourth Workshop on Computational Linguistics for Literature.
<li id="sravana" class="ref">Sravana, R., Knight, K. (2011). “Unsupervised discovery of rhyme schemes.” Proceedings of the 49th Annual Meeting of the Association for Computational Linguistics: shortpapers.
<li id="tobing" class="ref">Tobing, B. C. L. and Manurung, R. (2015). “A chart generation system for topical metrical poetry.” Proceedings of the Sixth International Conference on Computational Creativity (ICCC 2015).
</ul>


<script>

function return_random_stanza(){
        $.getJSON('https://api.myjson.com/bins/47w30', function(data) {
                var stanzas = []
                $.each ( data, function( key, val ) {
                    stanzas.push([key, val])
                    })
        var randomStanza = stanzas[Math.floor(Math.random() * stanzas.length)][1];
        randomStanza = randomStanza.replace(/\n/g, "<br />");
        console.log(randomStanza);
        $('#stanzap').append(randomStanza + "<br />");

  })};

function populate_quiz(){
    return_turing_stanza();
}


function return_turing_stanza(){
    $('div.pregunta').each(function(i,element){
        $.getJSON('https://api.myjson.com/bins/4vx1w', function(data) {
                var stanzas = []
                $.each ( data, function( key, val ) {
                    stanzas.push([key, val])
                    });
        var randomSelection = stanzas[Math.floor(Math.random() * stanzas.length)][1];
        var stanza = randomSelection['poem'];
        var label = randomSelection['label'];
        stanza = stanza.replace(/\n/g, "<br />");
        console.log(label);
        console.log(stanza);
        console.log(element);
        $(element).prepend("<p class='sample'>" + stanza + "</p>");
        $(element).append("<p class='answer'>" + label + "</p>");
        $('#quiz').show();
    });
  })};

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

        console.log(window.location);

        return false;
    });

});

$('#showanswer').click(function(){
    $('p.answer').show();
});


</script>
