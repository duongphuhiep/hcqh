(function() {

    var commonmark = require("commonmark");
    var reader = new commonmark.Parser();
    var writer = new commonmark.HtmlRenderer();

    /**
     * - concatenate the workingDir to every "plain image link" (plain image link is filename only and does not contain any '/'). eg
     *   - ![](image.jpg) will be transformed to ![](workingDir/image.jpg)
     *   - ![](abc/image.jpg) won't change
     * @param parsedMarkdown
     * @param workingDir
     */
    function fixPlainImageLink(parsedMarkdown, workingDir) {
        var walker = parsedMarkdown.walker();
        var event, node;

        while ((event = walker.next())) {
            node = event.node;
            if (node.type === 'Image' && event.entering) {
                var imageUrl = node.destination;
                if (imageUrl.indexOf('/') <= -1) { //if the imageUrl is plain
                    node.destination = workingDir+imageUrl;
                }
            }
        }

        return parsedMarkdown;
    }


    /**
     * Process the markdown content return the html content
     * - concatenate the workingDir to every "plain image link" (plain image link is filename only and does not contain any '/'). eg
     *   - ![](image.jpg) will be transformed to ![](workingDir/image.jpg)
     *   - ![](abc/image.jpg) won't change
     * If the markdown content starts with a HTML comment, it calls the header of the markdown.
     * then it will be extract to the returned result
     *
     * @param markdownContent: string
     * * @param workingDir: string
     * @return {header: string, html: string}
     */
    function process(markdownContent, workingDir) {
        var resu = {};
        var parsed = reader.parse(markdownContent);

        //extract the header

        var firstChild = parsed.firstChild;
        if (firstChild.type === 'HtmlBlock') {
            var textHead = firstChild.literal;
            if (textHead &&  textHead.indexOf("<!--")===0) {
                resu.header = textHead;
            }
        }

        //concatenate the workingDir to every "plain image link"
        if (workingDir) {
            parsed = fixPlainImageLink(parsed, workingDir);
        }

        resu.html = writer.render(parsed);
        return resu;
    }

    module.exports.process = process;

})();