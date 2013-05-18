milo
====

My solution to [this](https://www.codeeval.com/public_sc/48/) CodeEval challenge. It utilizes an implementation of the [Hungarian Algorithm](http://en.wikipedia.org/wiki/Hungarian_algorithm) that maximizes the weight of a cost matrix in polynomial time O(n<sup>3</sup>); includes a helper function ```padit``` that pads the matrix with zeroes to make dimensions square, and a function, called ```gcd```, that returns the greatest common denominator between two numbers.

A cost matrix of "suitability scores" (SS) is created by following these rules:
 * If the number of letters in the product's name is even then the SS is the number of vowels (a, e, i, o, u, y) in the customer's name multiplied by 1.5.
 * If the number of letters in the product's name is odd then the SS is the number of consonants in the customer's name.
 * If the number of letters in the product's name shares any common factors (besides 1) with the number of letters in the customer's name then the SS is multiplied by 1.5.

The script then solves the [assignment problem](http://en.wikipedia.org/wiki/Assignment_problem) of finding the maximal SS values in the cost matrix for each customer.

Input
-----

The script is run in the command-line and takes a path to a text file as its only argument. Each line in the file is one test case. Each test case will be a comma delimited set of customer names followed by a semicolon and then a comma delimited set of product names. For example (same as test.txt):

    Jack Abraham,John Evans,Ted Dziuba;iPad 2 - 4-pack,Girl Scouts Thin Mints,Nerf Crossbow
    Jeffery Lebowski,Walter Sobchak,Theodore Donald Kerabatsos,Peter Gibbons,Michael Bolton,Samir Nagheenanajar;Half & Half,Colt M1911A1,16lb bowling ball,Red Swingline Stapler,Printer paper,Vibe Magazine Subscriptions - 40 pack
    Jareau Wade,Rob Eroh,Mahmoud Abdelkader,Wenyi Cai,Justin Van Winkle,Gabriel Sinkin,Aaron Adelson;Batman No. 1,Football - Official Size,Bass Amplifying Headphones,Elephant food - 1024 lbs,Three Wolf One Moon T-shirt,Dom Perignon 2000 Vintage
    
Output
------

For each line of input, print out the maximum total score to two decimal places. For the example input above, the output should look like this:

    21.00
    83.50
    71.25
