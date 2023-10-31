<x-layout>
    <div class="content-container text-only">
        <div class="content-panel help">
            <h1 class="page-title">Using the Site</h1>

            <h3>Upload Colors</h3>
            <p><span class="green">Green</span> uploads have been uploaded by trusted users.</p>
            <p><span class="red">Red</span> uploads have been uploaded by restricted or new users, or have been reported more than once.</p>

            <h3>Styling Descriptions and Comments</h3>
            <p>You can style your comments and your torrent's description using Markdown. This includes adding images or linking to external websites. To link to an external site, use [label](https://example.com) where the text in the [] square brackets is the shown text of your link, and the URL in the () parentheses is the URL your link will point to. Embedding an image is similar. Use ![alt text](https://example.com/image.jpg) to have an image embedded in your comment or description. Note the ! exclamation mark at the beginning, denoting that this link is an image.</p>

            <h3>Changing Your User's Avatar</h3>
            <p>You may change or remove your avatar from <a href="{{ route('users.profile') }}">your profile</a>'s Preferences tab.</p>

            <h3>Getting Trusted Status</h3>
            <p>At the moment we have no established process for granting trusted status to users who did not previously have it. If and when we establish such a process it will be announced.</p>

            <h3>Help! My Upload/Comment Got Deleted!</h3>
            <p>Unlucky.</p>
        </div>
    </div>
</x-layout>