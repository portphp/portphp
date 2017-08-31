# Contributing

Please have a look at [CONTRIBUTING.md](https://github.com/portphp/portphp/blob/master/CONTRIBUTING.md).

## Building the documentation

The documentation for PortPHP is created using [MkDocs](http://www.mkdocs.org),
a lightweight solution based on Markdown.
 
The documentation is hosted on [Read the Docs](http://portphp.readthedocs.io).
To build the documentation on your local machine, clone the repository and
install the dependencies: 


```bash
$ git clone https://github.com/portphp/portphp.git
$ cd portphp
$ pip install -r docs/requirements.txt
```

Then run a live server:
 
```bash
 $ mkdocs serve
```

And open `http://127.0.0.1:8000` in your browser.

