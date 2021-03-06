<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Matcher\FileExistsMatcher;
use Matcher\FileHasContentsMatcher;
use Matcher\FileHasContentsMatchingMatcher;
use PhpSpec\Matcher\MatchersProviderInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Defines application features from the specific context.
 */
class FilesystemContext implements Context, MatchersProviderInterface
{
    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * @beforeScenario
     */
    public function prepWorkingDirectory()
    {
        $this->workingDirectory = tempnam(sys_get_temp_dir(), 'phpspec-behat');
        $this->filesystem->remove($this->workingDirectory);
        $this->filesystem->mkdir($this->workingDirectory);
        chdir($this->workingDirectory);
    }

    /**
     * @afterScenario
     */
    public function removeWorkingDirectory()
    {
        $this->filesystem->remove($this->workingDirectory);
    }

    /**
     * @Given the file :file contains:
     */
    public function theFileContains($file, PyStringNode $contents)
    {
        $this->filesystem->dumpFile($file, (string)$contents);
    }

    /**
     * @Then the file :file should contain:
     */
    public function theFileShouldContain($file, PyStringNode $contents)
    {
        expect($file)->toExist();
        expect($file)->toHaveContents($contents);
    }

    /**
     * @Then the file :file should match :regex
     */
    public function theFileShouldMatch($file, $regex)
    {
        expect($file)->toExist();
        expect($file)->toHaveContentsMatching($regex);
    }


    /**
     * @return array
     */
    public function getMatchers()
    {
        return array(
            new FileExistsMatcher(),
            new FileHasContentsMatcher(),
            new FileHasContentsMatchingMatcher()
        );
    }
}
